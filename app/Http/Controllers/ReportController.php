<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Attendee;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedEventId = session('selected_event_id');
        $activeEvent = ($selectedEventId && !session('global_mode')) ? Event::find($selectedEventId) : null;

        $withCounts = ['bookings', 'quotations', 'invoices'];
        if (Schema::hasColumn('expenses', 'event_id')) {
            $withCounts[] = 'expenses';
        }
        if (Schema::hasTable('event_sessions')) {
            $withCounts[] = 'sessions';
        }
        if (Schema::hasTable('tickets')) {
            $withCounts[] = 'tickets';
        }

        $events = Event::withCount($withCounts)->get();

        // Base scoped queries
        $bookingQuery   = Booking::query();
        $quotationQuery = Quotation::query();
        $invoiceQuery   = Invoice::query();
        $paymentQuery   = Payment::query();
        $attendeeQuery  = Attendee::query();
        $checkinQuery   = Checkin::query();

        if ($activeEvent) {
            $bookingQuery->where('event_id', $activeEvent->id);
            $quotationQuery->where('event_id', $activeEvent->id);
            $invoiceQuery->where('event_id', $activeEvent->id);
            $paymentQuery->whereHas('invoice', function($q) use ($activeEvent) {
                $q->where('event_id', $activeEvent->id);
            });
            $attendeeQuery->whereHas('booking', function($q) use ($activeEvent) {
                $q->where('event_id', $activeEvent->id);
            });
            $checkinQuery->where('event_id', $activeEvent->id);
        }

        $totalInvoiced          = (clone $invoiceQuery)->sum('total');
        $totalVerifiedPayments  = (clone $paymentQuery)->where('status', 'verified')->sum('amount_verified');
        $totalOutstanding       = (clone $invoiceQuery)->sum('amount_outstanding');
        $pendingQuotationValue  = (clone $quotationQuery)->where('status', 'pending')->sum('total');

        $confirmedBookingsCount = (clone $bookingQuery)->whereIn('status', ['confirmed', 'accepted'])->count();
        $pendingQuotationsCount = (clone $quotationQuery)->where('status', 'pending')->count();
        $rejectedQuotationsCount= (clone $quotationQuery)->where('status', 'rejected')->count();

        $totalAttendeesCount    = (clone $attendeeQuery)->count();
        $totalCheckinsCount     = Schema::hasTable('checkins') ? (clone $checkinQuery)->count() : 0;
        $uncheckedAttendeesCount= max(0, $totalAttendeesCount - $totalCheckinsCount);

        $stats = [
            'total_events' => $events->count(),
            'total_bookings' => (clone $bookingQuery)->count(),
            'total_invoiced' => $totalInvoiced,
            'total_verified_payments' => $totalVerifiedPayments,
            'total_outstanding' => $totalOutstanding,
            'total_attendees' => $totalAttendeesCount,
            'total_checkins' => $totalCheckinsCount,
        ];

        $chartFinancials = [
            'labels' => ['Verified Payments', 'Outstanding Receivables', 'Pending Quotation Value'],
            'data' => [$totalVerifiedPayments, $totalOutstanding, $pendingQuotationValue],
        ];

        $chartBookings = [
            'labels' => ['Confirmed Bookings', 'Pending Quotations', 'Rejected Applications'],
            'data' => [$confirmedBookingsCount, $pendingQuotationsCount, $rejectedQuotationsCount],
        ];

        $chartAttendees = [
            'labels' => ['Checked-In Attendees', 'Awaiting Check-In'],
            'data' => [$totalCheckinsCount, $uncheckedAttendeesCount],
        ];

        // Detailed Per-Event Breakdown Table Data
        $eventBreakdowns = $events->map(function($e) {
            $invoiced = Invoice::where('event_id', $e->id)->sum('total');
            $paid = Payment::whereHas('invoice', function($q) use ($e) {
                $q->where('event_id', $e->id);
            })->where('status', 'verified')->sum('amount_verified');
            $outstanding = Invoice::where('event_id', $e->id)->sum('amount_outstanding');
            $checkins = Schema::hasTable('checkins') ? Checkin::where('event_id', $e->id)->count() : 0;

            return [
                'id' => $e->id,
                'name' => $e->name,
                'code' => $e->event_code,
                'status' => $e->status,
                'invoiced' => $invoiced,
                'paid' => $paid,
                'outstanding' => $outstanding,
                'bookings_count' => $e->bookings_count,
                'quotations_count' => $e->quotations_count,
                'checkins_count' => $checkins,
            ];
        });

        return view('admin.reports.index', compact(
            'events', 'activeEvent', 'stats',
            'chartFinancials', 'chartBookings', 'chartAttendees',
            'eventBreakdowns'
        ));
    }

    public function export(Request $request, $type)
    {
        $selectedEventId = session('selected_event_id');
        $event = ($selectedEventId && !session('global_mode')) ? Event::find($selectedEventId) : null;

        $queryBookings = Booking::with(['client', 'event', 'invoice']);
        if ($event) {
            $queryBookings->where('event_id', $event->id);
        }
        $bookings = $queryBookings->get();

        if ($type === 'pdf') {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('admin.reports.pdf', compact('bookings', 'event'));
            return $pdf->download('MOSRAC_Event_Report_' . date('Ymd_His') . '.pdf');
        }

        return back()->with('info', 'Report export generated.');
    }
}
