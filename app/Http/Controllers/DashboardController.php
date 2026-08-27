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

        // --- Core Metrics ---
        $totalEvents = Event::count();
        $upcomingEventsCount = Event::where('start_date', '>', Carbon::now())->count();
        $totalExhibitors = Client::count(); // Exhibitors are represented by Clients

        $pendingQuotations = Quotation::where('status', 'pending')->count();
        $approvedQuotations = Quotation::where('status', 'approved')->count();
        $rejectedQuotations = Quotation::where('status', 'rejected')->count();
        
        $confirmedBookings = Booking::whereIn('status', ['confirmed', 'accepted'])->count();
        $totalAttendees = Attendee::count();
        
        // Use the Payment model if it exists to find pending payments
        // Assuming 'status' = 0 or 'pending'
        $pendingPaymentsCount = class_exists(\App\Models\Payment::class) ? 
                                \App\Models\Payment::whereIn('status', ['pending', 0])->count() : 0;
        
        $totalInvoiced = Invoice::sum('total');
        $totalPaid = Invoice::sum('amount_paid');
        $outstandingBalance = Invoice::sum('amount_outstanding');

        // --- Recent Data for Tables ---
        $recentQuotations = Quotation::with('client')->latest()->take(5)->get();
        $recentBookings = Booking::with('client')->latest()->take(5)->get();
        $recentPayments = class_exists(\App\Models\Payment::class) ? 
                          \App\Models\Payment::with('client')->latest()->take(5)->get() : collect();
        $upcomingEvents = Event::where('start_date', '>', Carbon::now())->orderBy('start_date', 'asc')->take(5)->get();

        return view('dashboard')->with([
            'totalEvents' => $totalEvents,
            'upcomingEventsCount' => $upcomingEventsCount,
            'totalExhibitors' => $totalExhibitors,
            'pendingQuotations' => $pendingQuotations,
            'approvedQuotations' => $approvedQuotations,
            'rejectedQuotations' => $rejectedQuotations,
            'confirmedBookings' => $confirmedBookings,
            'totalAttendees' => $totalAttendees,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'totalInvoiced' => $totalInvoiced,
            'totalPaid' => $totalPaid,
            'outstandingBalance' => $outstandingBalance,
            
            'recentQuotations' => $recentQuotations,
            'recentBookings' => $recentBookings,
            'recentPayments' => $recentPayments,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
