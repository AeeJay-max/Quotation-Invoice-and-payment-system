<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function customerPayments()
    {
        $user = Auth::user();
        $clientId = $user->client_id ?? optional($user->client()->first())->id;

        $quotations = \App\Models\Quotation::where('client_id', $clientId)
            ->orWhere('user_id', $user->id)
            ->get();

        $bankSettings = \App\Models\Settings::where('type', 'email')->pluck('description', 'label')->toArray();

        $payments = Payment::where('client_id', $clientId)
            ->with(['invoice', 'quotation', 'booking.event'])
            ->latest()
            ->get();

        return view('customer.payments.index', compact('invoices', 'quotations', 'payments', 'bankSettings'));
    }

    public function submitPayment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'quotation_number' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'transaction_reference' => 'required|string|max:100',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::with(['booking', 'quotation'])->findOrFail($validated['invoice_id']);
        $quotation = $invoice->quotation ?? \App\Models\Quotation::where('quotation_number', $validated['quotation_number'])->first();

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('payments', 'public');
        }

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'booking_id' => $invoice->booking_id,
            'quotation_id' => $quotation ? $quotation->id : $invoice->quotation_id,
            'quotation_number' => $validated['quotation_number'],
            'client_id' => $invoice->client_id,
            'amount' => $validated['amount'],
            'currency' => $invoice->event->currency ?? 'USD',
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'],
            'proof_of_payment_path' => $proofPath,
            'payment_date' => now(),
            'status' => 'submitted',
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($invoice->booking) {
            BookingStatusHistory::create([
                'booking_id' => $invoice->booking_id,
                'user_id' => Auth::id(),
                'status' => 'Payment Submitted',
                'notes' => "Customer submitted payment of {$payment->currency} {$payment->amount} (Ref: {$payment->transaction_reference}).",
            ]);
        }

        return back()->with('success', 'Payment proof submitted successfully! Pending administrator verification.');
    }

    public function adminVerifyPayment($id)
    {
        $payment = Payment::with('invoice.booking')->findOrFail($id);

        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'verified']);

            $invoice = $payment->invoice;
            if ($invoice) {
                $newPaid = floatval($invoice->amount_paid) + floatval($payment->amount);
                $newOutstanding = max(0, floatval($invoice->amount_outstanding) - floatval($payment->amount));
                
                $paymentStatus = $newOutstanding <= 0 ? 2 : 3; // 2 = Paid, 3 = Partially Paid in lookup
                
                $invoice->update([
                    'amount_paid' => $newPaid,
                    'amount_outstanding' => $newOutstanding,
                    'payment_status' => $paymentStatus,
                ]);

                if ($invoice->booking) {
                    $bStatus = $newOutstanding <= 0 ? 'paid' : 'partially_paid';
                    $invoice->booking->update(['payment_status' => $bStatus]);

                    BookingStatusHistory::create([
                        'booking_id' => $invoice->booking->id,
                        'user_id' => Auth::id(),
                        'status' => 'Payment Verified',
                        'notes' => "Admin verified payment of {$payment->currency} {$payment->amount}. Outstanding balance: {$payment->currency} {$newOutstanding}.",
                    ]);
                }
            }
        });

        return back()->with('success', 'Payment verified and invoice updated.');
    }

    public function adminRejectPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'rejected']);

        return back()->with('success', 'Payment rejected.');
    }
}
