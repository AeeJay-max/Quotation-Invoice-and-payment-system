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

        $invoices = Invoice::all();
        $quotations = Quotation::all();
        $users = User::where('is_admin', true)->orWhere('role_id', 1)->get();
        $clients = Client::all();
        
        $totalEvents = Event::count();
        $activeEvents = Event::whereIn('status', ['published', 'registration_open'])->count();
        $totalBookings = Booking::count();
        $confirmedBookings = Booking::whereIn('status', ['confirmed', 'accepted'])->count();
        $pendingQuotations = Quotation::where('status', 'pending')->count();
        $totalAttendees = Attendee::count();
        $approvedAttendees = Attendee::where('status', 'approved')->count();
        $badgesPrinted = Badge::where('status', 'printed')->count();
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'accepted'])->sum('grand_total');

        return view('dashboard')->with([
            'invoices' => $invoices,
            'quotations' => $quotations,
            'users' => $users,
            'clients' => $clients,
            'totalEvents' => $totalEvents,
            'activeEvents' => $activeEvents,
            'totalBookings' => $totalBookings,
            'confirmedBookings' => $confirmedBookings,
            'pendingQuotations' => $pendingQuotations,
            'totalAttendees' => $totalAttendees,
            'approvedAttendees' => $approvedAttendees,
            'badgesPrinted' => $badgesPrinted,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}
