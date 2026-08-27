<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════
    //  CUSTOMER PORTAL
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Customer: list invoices + payment history.
     */
    public function customerPayments()
    {
        $user     = Auth::user();
        $clientId = $user->client_id ?? optional($user->clientRecord)->id;

        $invoices = Invoice::where('client_id', $clientId)
            ->with('quotation')
            ->latest()
            ->get();

        $quotations = \App\Models\Quotation::where('client_id', $clientId)
            ->latest()
            ->get();

        $bankSettings = Settings::where('type', 'email')
            ->pluck('description', 'label')
            ->toArray();

        $payments = Payment::where('client_id', $clientId)
            ->with(['invoice', 'quotation', 'booking.event'])
            ->latest()
            ->get();

        return view('customer.payments.index',
            compact('invoices', 'quotations', 'payments', 'bankSettings'));
    }

    /**
     * Customer: submit proof of payment.
     *
     * The submitted amount is stored as amount_claimed and does NOT update
     * the invoice balance. Balance only updates when admin verifies.
     */
    public function submitPayment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id'            => 'required|exists:invoices,id',
            'quotation_number'      => 'required|string',
            'amount'                => 'required|numeric|min:0.01',
            'payment_method'        => 'required|string',
            'transaction_reference' => 'required|string|max:100',
            'proof_file'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes'                 => 'nullable|string',
        ]);

        $user     = Auth::user();
        $clientId = $user->client_id ?? optional($user->clientRecord)->id;

        // AUTHORIZATION: invoice must belong to this client
        $invoice = Invoice::with(['booking', 'quotation'])
            ->where('id', $validated['invoice_id'])
            ->where('client_id', $clientId)
            ->firstOrFail();

        $quotation = $invoice->quotation
            ?? \App\Models\Quotation::where('quotation_number', $validated['quotation_number'])
                ->where('client_id', $clientId)
                ->first();

        // Store proof in private storage (not public) so only auth users can access
        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')
                ->store('payment-proofs', 'local');  // stored in storage/app/payment-proofs/
        }

        $claimedAmount = floatval($validated['amount']);

        $payment = Payment::create([
            'invoice_id'            => $invoice->id,
            'booking_id'            => $invoice->booking_id,
            'quotation_id'          => $quotation ? $quotation->id : $invoice->quotation_id,
            'quotation_number'      => $validated['quotation_number'],
            'client_id'             => $invoice->client_id,
            'amount'                => $claimedAmount,       // backward-compat
            'amount_claimed'        => $claimedAmount,       // authoritative claimed field
            'amount_verified'       => null,                 // null until admin verifies
            'currency'              => optional($invoice->event)->currency ?? 'USD',
            'payment_method'        => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'],
            'proof_of_payment_path' => $proofPath,
            'payment_date'          => now(),
            'status'                => 'submitted',          // pending admin verification
            'notes'                 => $validated['notes'] ?? null,
        ]);

        // Record booking status history but do NOT change payment status
        if ($invoice->booking) {
            BookingStatusHistory::create([
                'booking_id' => $invoice->booking_id,
                'user_id'    => Auth::id(),
                'status'     => 'Payment Submitted – Pending Verification',
                'notes'      => "Exhibitor submitted payment proof claiming {$payment->currency} " .
                                number_format($claimedAmount, 2) .
                                " (Ref: {$payment->transaction_reference}). Awaiting admin verification.",
            ]);
        }

        return back()->with('success',
            'Payment proof submitted successfully. The Ministry will verify your payment and update your balance accordingly.');
    }

    /**
     * Customer: serve own proof of payment file securely.
     */
    public function customerServeProof($id)
    {
        $user     = Auth::user();
        $clientId = $user->client_id ?? optional($user->clientRecord)->id;

        $payment = Payment::where('id', $id)
            ->where('client_id', $clientId)
            ->firstOrFail();

        return $this->streamProofFile($payment);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  ADMIN PORTAL
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Admin: payment list with summary cards.
     */
    public function adminPayments()
    {
        $payments = Payment::with([
            'client',
            'invoice',
            'booking.event',
            'quotation',
            'verifiedBy',
            'rejectedBy',
        ])->latest()->get();

        $summary = [
            'total'         => $payments->count(),
            'pending'       => $payments->whereIn('status', ['submitted', 'pending'])->count(),
            'verified'      => $payments->where('status', 'verified')->count(),
            'rejected'      => $payments->where('status', 'rejected')->count(),
            'total_verified_amount' => $payments->where('status', 'verified')->sum('amount_verified'),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    /**
     * Admin: payment detail / verification page.
     */
    public function adminPaymentShow($id)
    {
        $payment = Payment::with([
            'client',
            'invoice.items',
            'booking.event',
            'quotation',
            'verifiedBy',
            'rejectedBy',
        ])->findOrFail($id);

        $invoice = $payment->invoice;

        // Calculate totals from VERIFIED payments only (current state before this payment)
        $previouslyVerified = 0;
        if ($invoice) {
            $previouslyVerified = Payment::where('invoice_id', $invoice->id)
                ->where('status', 'verified')
                ->where('id', '!=', $payment->id)
                ->sum('amount_verified');
        }

        return view('admin.payments.show', compact('payment', 'invoice', 'previouslyVerified'));
    }

    /**
     * Admin: verify payment with a specific verified amount.
     * Uses DB transaction. Prevents double-verification.
     */
    public function adminVerifyPayment(Request $request, $id)
    {
        $request->validate([
            'verified_amount' => 'required|numeric|min:0',
        ]);

        $payment = Payment::with('invoice.booking')->findOrFail($id);

        // AUTHORIZATION: only admins
        if (!Auth::user() || Auth::user()->role_id != 1) {
            abort(403, 'Unauthorized');
        }

        // Prevent double-verification
        if ($payment->status === 'verified') {
            return back()->with('error', 'This payment has already been verified.');
        }

        $verifiedAmount = floatval($request->verified_amount);

        DB::transaction(function () use ($payment, $verifiedAmount) {
            // 1. Mark payment verified with the admin-confirmed amount
            $payment->update([
                'status'          => 'verified',
                'amount_verified' => $verifiedAmount,
                'verified_by'     => Auth::id(),
                'verified_at'     => now(),
                'rejection_reason'=> null,
            ]);

            $invoice = $payment->invoice;
            if ($invoice) {
                // 2. Recalculate from ALL verified payments (source of truth — never trust old cached values)
                $totalVerified = Payment::where('invoice_id', $invoice->id)
                    ->where('status', 'verified')
                    ->sum('amount_verified');

                $invoiceTotal      = floatval($invoice->total ?? 0);
                $newOutstanding    = max(0, $invoiceTotal - $totalVerified);
                $paymentStatus     = $newOutstanding <= 0 ? 2 : 3; // 2=Paid, 3=Partially Paid

                // 3. Update invoice with recalculated values
                $invoice->update([
                    'amount_paid'        => $totalVerified,
                    'amount_outstanding' => $newOutstanding,
                    'payment_status'     => $paymentStatus,
                ]);

                // 4. Update booking payment status
                if ($invoice->booking) {
                    $bStatus = $newOutstanding <= 0 ? 'paid' : 'partially_paid';
                    $invoice->booking->update(['payment_status' => $bStatus]);

                    BookingStatusHistory::create([
                        'booking_id' => $invoice->booking->id,
                        'user_id'    => Auth::id(),
                        'status'     => 'Payment Verified',
                        'notes'      => sprintf(
                            'Admin verified payment of %s %s (claimed: %s %s). Total verified: %s %s. Outstanding: %s %s.',
                            $payment->currency, number_format($verifiedAmount, 2),
                            $payment->currency, number_format(floatval($payment->amount_claimed), 2),
                            $payment->currency, number_format($totalVerified, 2),
                            $payment->currency, number_format($newOutstanding, 2)
                        ),
                    ]);
                }
            }
        });

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment verified successfully. Invoice balance updated.');
    }

    /**
     * Admin: reject payment with a required reason.
     */
    public function adminRejectPayment(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (!Auth::user() || Auth::user()->role_id != 1) {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::findOrFail($id);

        if ($payment->status === 'verified') {
            return back()->with('error', 'Cannot reject an already verified payment.');
        }

        $payment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by'      => Auth::id(),
            'rejected_at'      => now(),
        ]);

        // Record history but do NOT touch invoice balance
        if ($payment->booking_id) {
            BookingStatusHistory::create([
                'booking_id' => $payment->booking_id,
                'user_id'    => Auth::id(),
                'status'     => 'Payment Rejected',
                'notes'      => 'Admin rejected payment submission. Reason: ' . $request->rejection_reason,
            ]);
        }

        return redirect()->route('admin.payments.index')
            ->with('error', 'Payment has been rejected. Invoice balance unchanged.');
    }

    /**
     * Admin: serve proof of payment file securely (private storage).
     */
    public function adminServeProof($id)
    {
        if (!Auth::user() || Auth::user()->role_id != 1) {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::findOrFail($id);
        return $this->streamProofFile($payment);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════════════════

    private function streamProofFile(Payment $payment)
    {
        if (!$payment->proof_of_payment_path) {
            abort(404, 'No proof of payment on file.');
        }

        $path = $payment->proof_of_payment_path;

        // Support both old public-disk paths and new local-disk paths
        if (Storage::disk('local')->exists($path)) {
            $disk    = 'local';
            $content = Storage::disk('local')->get($path);
            $mime    = Storage::disk('local')->mimeType($path);
        } elseif (Storage::disk('public')->exists($path)) {
            $disk    = 'public';
            $content = Storage::disk('public')->get($path);
            $mime    = Storage::disk('public')->mimeType($path);
        } else {
            abort(404, 'Proof file not found.');
        }

        $filename = basename($path);
        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
