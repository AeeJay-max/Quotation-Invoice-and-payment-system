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

        $totalPaid = 0;
        $totalBalance = 0;
        $grandTotal = 0;
        $paidPercentage = 0;

        if ($activeBooking && $activeBooking->invoice) {
            $grandTotal = $activeBooking->invoice->total > 0 ? $activeBooking->invoice->total : $activeBooking->grand_total;
            $totalPaid = $activeBooking->invoice->amount_paid;
            $totalBalance = $activeBooking->invoice->amount_outstanding;
            if ($grandTotal > 0) {
                $paidPercentage = min(100, round(($totalPaid / $grandTotal) * 100));
            }
        } elseif ($activeBooking) {
            $grandTotal = $activeBooking->grand_total;
        }

        $attendeeCount = $activeBooking ? $activeBooking->attendees->count() : 0;
        $approvedAttendees = $activeBooking ? $activeBooking->attendees->where('status', 'approved')->count() : 0;
        $badgesGenerated = $activeBooking ? $activeBooking->badges->count() : 0;
        $bankDetails = $this->getBankDetails();

        return view('customer.dashboard', compact(
            'bookings',
            'activeBooking',
            'grandTotal',
            'totalPaid',
            'totalBalance',
            'paidPercentage',
            'attendeeCount',
            'approvedAttendees',
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
