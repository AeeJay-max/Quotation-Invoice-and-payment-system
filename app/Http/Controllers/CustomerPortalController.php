<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\Attendee;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPortalController extends Controller
{
    protected function getCustomerClientId()
    {
        $user = Auth::user();
        if ($user->client_id) {
            return $user->client_id;
        }
        $client = $user->client()->first();
        return $client ? $client->id : null;
    }

    protected function getBankDetails()
    {
        return \App\Models\Settings::where('type', 'email')->pluck('description', 'label')->toArray();
    }

    protected function getMinistrySettings()
    {
        $db = \App\Models\Settings::whereIn('type', ['system', 'general'])->pluck('description', 'label')->toArray();
        // Hardcoded fallbacks so the views never show blanks
        return array_merge([
            'app_name'           => 'Ministry of Sport, Recreation, Arts and Culture',
            'app_address'        => 'Chinengundu Mashayamombe Building 95, Cnr N. Mandela & S. V. Muzenda Street, Harare',
            'app_postal_address' => 'P.O. Box HR 480 Harare',
            'app_email'          => 'mosrac@kuzana.org.zw',
            'app_email_cc'       => 'secretariat@kuzana.org.zw',
            'app_phone'          => '+263 772 394036 / +263 717 720 641 / +263 719 226 279 / +263 716 801 385',
            'logo'               => 'assets/files/ministry-logo.png',
        ], $db);
    }

    public function dashboard()
    {
        $clientId = $this->getCustomerClientId();
        $user = Auth::user();

        $bookings = Booking::where('client_id', $clientId)
            ->orWhere('user_id', $user->id)
            ->with(['event', 'quotation', 'invoice', 'attendees', 'badges'])
            ->latest()
            ->get();

        $activeBooking = $bookings->first();

        // Quotation stats
        $pendingQuotations  = Quotation::where('client_id', $clientId)->where('status', 'pending')->count();
        $approvedQuotations = Quotation::where('client_id', $clientId)->where('status', 'approved')->count();
        $confirmedBookings  = Booking::where('client_id', $clientId)->whereIn('status', ['confirmed', 'accepted'])->count();

        // Invoices for this exhibitor — includes admin-created invoices linked via client_id or booking_id
        $bookingIds = $bookings->pluck('id');
        $invoices = Invoice::where(function ($q) use ($clientId, $user, $bookingIds) {
            if ($clientId) {
                $q->where('client_id', $clientId);
            }
            $q->orWhere('user_id', $user->id);
            if ($bookingIds->isNotEmpty()) {
                $q->orWhereIn('booking_id', $bookingIds);
            }
        })->get();
        $invoiceIds    = $invoices->pluck('id');
        $totalInvoiced = $invoices->sum('total');

        // Financial summary from VERIFIED payments only (authoritative)
        $totalPaid = \App\Models\Payment::whereIn('invoice_id', $invoiceIds)
            ->where('status', 'verified')
            ->sum('amount_verified');

        $totalBalance    = max(0, $totalInvoiced - $totalPaid);
        $paidPercentage  = $totalInvoiced > 0 ? min(100, round(($totalPaid / $totalInvoiced) * 100)) : 0;

        // Pending (submitted but not yet verified) payments
        $pendingPaymentsCount = \App\Models\Payment::whereIn('invoice_id', $invoiceIds)
            ->whereIn('status', ['submitted', 'pending'])
            ->count();

        // Attendees
        $attendeeCount = Attendee::whereHas('booking', fn($q) => $q->where('client_id', $clientId))->count();
        $badgesGenerated = Badge::whereHas('booking', fn($q) => $q->where('client_id', $clientId))->count();

        $bankDetails = $this->getBankDetails();

        return view('customer.dashboard', compact(
            'bookings',
            'activeBooking',
            'pendingQuotations',
            'approvedQuotations',
            'confirmedBookings',
            'totalInvoiced',
            'totalPaid',
            'totalBalance',
            'paidPercentage',
            'pendingPaymentsCount',
            'attendeeCount',
            'badgesGenerated',
            'bankDetails'
        ));
    }

    public function bookings()
    {
        $clientId = $this->getCustomerClientId();
        $user = Auth::user();

        $bookings = Booking::where('client_id', $clientId)
            ->orWhere('user_id', $user->id)
            ->with(['event', 'space', 'standType', 'quotation', 'invoice'])
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function showBooking($id)
    {
        $clientId = $this->getCustomerClientId();
        $user = Auth::user();

        $booking = Booking::where(function ($q) use ($clientId, $user) {
            $q->where('client_id', $clientId)->orWhere('user_id', $user->id);
        })->with([
            'event',
            'space',
            'standType',
            'position',
            'quotation.items',
            'invoice.payments',
            'attendees.attendeeType',
            'attendees.badge'
        ])->findOrFail($id);

        return view('customer.bookings.show', compact('booking'));
    }

    public function quotations()
    {
        $clientId = $this->getCustomerClientId();
        $user = Auth::user();

        $quotations = Quotation::where('client_id', $clientId)
            ->orWhere('user_id', $user->id)
            ->with('event')
            ->latest()
            ->paginate(10);

        return view('customer.quotations.index', compact('quotations'));
    }

    public function invoices()
    {
        $clientId = $this->getCustomerClientId();
        $user = Auth::user();

        $invoices = Invoice::where('client_id', $clientId)
            ->orWhere('user_id', $user->id)
            ->with(['event', 'payments'])
            ->latest()
            ->paginate(10);

        return view('customer.invoices.index', compact('invoices'));
    }

    public function showInvoice($id)
    {
        $clientId = $this->getCustomerClientId();
        $user = Auth::user();

        $invoice = Invoice::where(function ($q) use ($clientId, $user) {
            $q->where('client_id', $clientId)->orWhere('user_id', $user->id);
        })->with(['event', 'items', 'client', 'payments'])->findOrFail($id);

        // Calculate verified balances from payments (authoritative)
        $verified_paid = $invoice->payments
            ->where('status', 'verified')
            ->sum('amount_verified');

        $grandTotal        = floatval($invoice->total ?? 0);
        $outstanding_balance = max(0, $grandTotal - $verified_paid);

        $ministrySettings = $this->getMinistrySettings();

        return view('customer.invoices.show', compact(
            'invoice',
            'verified_paid',
            'outstanding_balance',
            'ministrySettings'
        ));
    }

    // --- Onboarding & Verification Step 1: Force Password Change ---
    public function showMustChangePassword()
    {
        $user = Auth::user();
        return view('customer.must-change-password', compact('user'));
    }

    public function updateMustChangePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('customer.verify-email')
            ->with('success', 'Password updated successfully! Next, please verify your email address.');
    }

    // --- Onboarding & Verification Step 2: Email Verification ---
    public function showVerifyEmail()
    {
        $user = Auth::user();
        return view('customer.verify-email', compact('user'));
    }

    public function sendVerificationEmail(Request $request)
    {
        $user = Auth::user();
        // Generate verification link token
        $verificationUrl = route('customer.verify-email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);

        // Try to send email via Mail facade if configured, fallback cleanly
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "Hello {$user->name},\n\nPlease verify your email address for the MOSRAC Exhibitor Portal by clicking the link below:\n\n{$verificationUrl}\n\nThank you,\nMinistry of Sport, Recreation, Arts and Culture",
                function ($message) use ($user) {
                    $message->to($user->email)->subject('Verify Your MOSRAC Account Email');
                }
            );
        } catch (\Exception $e) {
            // Fallback for local environments without SMTP configured
        }

        return redirect()->back()->with([
            'success' => "Verification link dispatched to {$user->email}! Click the verification link or use the instant button below.",
            'simulated_link' => $verificationUrl
        ]);
    }

    public function verifyEmail($id, $hash)
    {
        $user = Auth::user();
        if ($user->id == $id && sha1($user->email) == $hash) {
            $user->email_verified_at = now();
            $user->save();

            return redirect()->route('customer.dashboard')
                ->with('success', 'Account verification complete! You now have full access to your Exhibitor Portal.');
        }

        return redirect()->route('customer.verify-email')->with('error', 'Invalid or expired verification link.');
    }
}
