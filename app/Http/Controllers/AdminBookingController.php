<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['event', 'client', 'quotation', 'invoice', 'attendees', 'badges'])->latest();

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(15);
        $events = Event::all();

        return view('admin.bookings.index', compact('bookings', 'events'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'event',
            'client.user',
            'quotation.items',
            'invoice.payments',
            'space',
            'standType',
            'position',
            'items',
            'attendees.attendeeType',
            'attendees.badge',
            'badges',
            'payments',
            'statusHistories.user'
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:submitted,under_review,confirmed,accepted,cancelled,rejected',
            'notes' => 'nullable|string',
        ]);

        $booking->update(['status' => $validated['status']]);

        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'status' => ucfirst($validated['status']),
            'notes' => $validated['notes'] ?? "Status updated by admin to {$validated['status']}.",
        ]);

        return back()->with('success', 'Booking status updated successfully.');
    }
}
