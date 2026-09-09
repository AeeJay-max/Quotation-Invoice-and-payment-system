<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Attendee;
use App\Models\Badge;
use App\Models\Payment;
use App\Models\Checkin;
use App\Models\Task;
use App\Models\EventIncident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->role && strtolower($user->role->name) === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        $allEvents = Event::latest()->get();
        $selectedEventId = session('selected_event_id');
        $selectedEvent = $selectedEventId ? Event::find($selectedEventId) : null;

        // Base queries
        $quotationQuery = Quotation::query();
        $bookingQuery   = Booking::query();
        $invoiceQuery   = Invoice::query();
        $paymentQuery   = Payment::query();
        $attendeeQuery  = Attendee::query();
        $checkinQuery   = Checkin::query();
        $taskQuery      = Task::query();

        if ($selectedEventId && !session('global_mode')) {
            $quotationQuery->where('event_id', $selectedEventId);
            $bookingQuery->where('event_id', $selectedEventId);
            $invoiceQuery->where('event_id', $selectedEventId);
            $paymentQuery->whereHas('invoice', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            });
            $attendeeQuery->whereHas('booking', function($q) use ($selectedEventId) {
                $q->where('event_id', $selectedEventId);
            });
            $checkinQuery->where('event_id', $selectedEventId);
            $taskQuery->where('event_id', $selectedEventId);
        }

        // --- Core Metrics ---
        $totalEvents = Event::count();
        $upcomingEventsCount = Event::where('start_date', '>', Carbon::now())->count();
        $totalExhibitors = Client::count();

        $pendingQuotations  = (clone $quotationQuery)->where('status', 'pending')->count();
        $approvedQuotations = (clone $quotationQuery)->where('status', 'approved')->count();
        $rejectedQuotations = (clone $quotationQuery)->where('status', 'rejected')->count();
        
        $confirmedBookings = (clone $bookingQuery)->whereIn('status', ['confirmed', 'accepted'])->count();
        $totalAttendees    = (clone $attendeeQuery)->count();
        $totalCheckins     = \Illuminate\Support\Facades\Schema::hasTable('checkins') ? (clone $checkinQuery)->count() : 0;

        $pendingPaymentsCount = (clone $paymentQuery)->whereIn('status', ['submitted', 'pending'])->count();
        $totalPaidVerified    = (clone $paymentQuery)->where('status', 'verified')->sum('amount_verified');
        
        $totalInvoiced      = (clone $invoiceQuery)->sum('total');
        $totalPaid          = (clone $invoiceQuery)->sum('amount_paid');
        $outstandingBalance = (clone $invoiceQuery)->sum('amount_outstanding');

        $urgentTasksCount = \Illuminate\Support\Facades\Schema::hasTable('tasks') ? (clone $taskQuery)->where('priority', 'URGENT')->where('status', '!=', 'COMPLETED')->count() : 0;

        // --- Recent Data for Tables ---
        $recentQuotations = (clone $quotationQuery)->with('client')->latest()->take(5)->get();
        $recentBookings   = (clone $bookingQuery)->with('client')->latest()->take(5)->get();
        $recentPayments   = (clone $paymentQuery)->with('client')->latest()->take(5)->get();
        $upcomingEvents   = Event::where('start_date', '>', Carbon::now())->orderBy('start_date', 'asc')->take(5)->get();

        return view('dashboard')->with([
            'allEvents' => $allEvents,
            'selectedEvent' => $selectedEvent,
            'totalEvents' => $totalEvents,
            'upcomingEventsCount' => $upcomingEventsCount,
            'totalExhibitors' => $totalExhibitors,
            'pendingQuotations' => $pendingQuotations,
            'approvedQuotations' => $approvedQuotations,
            'rejectedQuotations' => $rejectedQuotations,
            'confirmedBookings' => $confirmedBookings,
            'totalAttendees' => $totalAttendees,
            'totalCheckins' => $totalCheckins,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'totalPaidVerified' => $totalPaidVerified,
            'totalInvoiced' => $totalInvoiced,
            'totalPaid' => $totalPaid,
            'outstandingBalance' => $outstandingBalance,
            'urgentTasksCount' => $urgentTasksCount,
            
            'recentQuotations' => $recentQuotations,
            'recentBookings' => $recentBookings,
            'recentPayments' => $recentPayments,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
