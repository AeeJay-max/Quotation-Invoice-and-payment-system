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
        $db = \App\Models\Settings::where('type', 'general')->pluck('description', 'label')->toArray();
        // Hardcoded fallbacks so the views never show blanks
        return array_merge([
            'app_name'           => 'Ministry of Sports, Recreation, Arts and Culture',
            'app_address'        => 'Chinengundu Mashayamombe Building 95, Cnr N. Mandela & S. V. Muzenda Street, Harare',
            'app_postal_address' => 'P.O. Box HR 480 Harare',
            'app_email'          => 'minofsportandarts@gmail.com',
            'app_phone'          => '+263242708345',
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

        // Invoices for this exhibitor
        $invoices      = Invoice::where('client_id', $clientId)->get();
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
}
