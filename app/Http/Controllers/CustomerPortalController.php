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

        // Calculate overarching stats for the dashboard cards
        $pendingQuotations = Quotation::where('client_id', $clientId)->where('status', 'pending')->count();
        $approvedQuotations = Quotation::where('client_id', $clientId)->where('status', 'approved')->count();
        $confirmedBookings = Booking::where('client_id', $clientId)->whereIn('status', ['confirmed', 'accepted'])->count();

        // Overall finance for the exhibitor
        $invoices = Invoice::where('client_id', $clientId)->get();
        $totalInvoiced = $invoices->sum('total');
        $totalPaid = $invoices->sum('amount_paid');
        $totalBalance = $invoices->sum('amount_outstanding');
        $paidPercentage = $totalInvoiced > 0 ? min(100, round(($totalPaid / $totalInvoiced) * 100)) : 0;

        // Overall Attendees and Badges
        $attendeeCount = Attendee::whereHas('booking', function ($q) use ($clientId) {
            $q->where('client_id', $clientId);
        })->count();
        
        $badgesGenerated = Badge::whereHas('booking', function ($q) use ($clientId) {
            $q->where('client_id', $clientId);
        })->count();

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

        return view('customer.invoices.show', compact('invoice'));
    }
}
