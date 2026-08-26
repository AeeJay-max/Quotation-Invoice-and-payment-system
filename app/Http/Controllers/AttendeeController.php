<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\AttendeeType;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Services\BadgeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendeeController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeGeneratorService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    // Customer Side
    public function index(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id ?? optional($user->client()->first())->id;

        $bookings = Booking::where('client_id', $clientId)
            ->orWhere('user_id', $user->id)
            ->with(['event.attendeeTypes', 'attendees.attendeeType', 'attendees.badge'])
            ->get();

        $selectedBookingId = $request->get('booking_id', optional($bookings->first())->id);
        $selectedBooking = $selectedBookingId ? Booking::with(['event.attendeeTypes', 'attendees.attendeeType', 'attendees.badge'])->find($selectedBookingId) : null;

        return view('customer.attendees.index', compact('bookings', 'selectedBooking'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'attendee_type_id' => 'nullable|exists:attendee_types,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
            'id_passport' => 'nullable|string|max:100',
            'special_requirements' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);
        $validated['company'] = $booking->client->company_name ?? '';
        $validated['status'] = 'draft';

        Attendee::create($validated);

        // Recalculate attendee costs
        $this->recalculateAttendeeTotal($booking);

        return back()->with('success', 'Attendee added to list.');
    }

    public function update(Request $request, $id)
    {
        $attendee = Attendee::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'title' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'position' => 'nullable|string',
            'id_passport' => 'nullable|string',
        ]);

        $attendee->update($validated);
        $this->recalculateAttendeeTotal($attendee->booking);

        return back()->with('success', 'Attendee updated successfully.');
    }

    public function destroy($id)
    {
        $attendee = Attendee::findOrFail($id);
        $booking = $attendee->booking;
        $attendee->delete();

        $this->recalculateAttendeeTotal($booking);

        return back()->with('success', 'Attendee removed from list.');
    }

    public function submitList(Request $request, $bookingId)
    {
        $booking = Booking::with('attendees')->findOrFail($bookingId);

        if ($booking->attendees->isEmpty()) {
            return back()->with('error', 'Please add at least one attendee before submitting.');
        }

        DB::transaction(function () use ($booking) {
            $booking->attendees()->update(['status' => 'submitted']);
            $booking->update(['attendee_status' => 'submitted']);

            BookingStatusHistory::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'status' => 'Attendee List Submitted',
                'notes' => "Customer submitted list of {$booking->attendees->count()} attendees.",
            ]);
        });

        return back()->with('success', 'Attendee list submitted to event administrator for processing.');
    }

    // Helper for attendee calculation
    protected function recalculateAttendeeTotal(Booking $booking)
    {
        $total = 0;
        foreach ($booking->attendees as $attendee) {
            if ($attendee->attendeeType) {
                $total += floatval($attendee->attendeeType->price);
            }
        }
        $booking->update(['attendee_total' => $total]);
    }

    // Admin Side
    public function adminIndex(Request $request)
    {
        $query = Attendee::with(['booking.client', 'booking.event', 'attendeeType', 'badge'])->latest();

        if ($request->filled('event_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendees = $query->paginate(20);
        $events = \App\Models\Event::all();

        return view('admin.attendees.index', compact('attendees', 'events'));
    }

    public function approveAttendee($id)
    {
        $attendee = Attendee::with('booking.event')->findOrFail($id);
        $attendee->update(['status' => 'approved']);

        // Generate Badge
        $this->badgeService->generateForAttendee($attendee);

        return back()->with('success', "Attendee {$attendee->full_name} approved and badge generated.");
    }

    public function rejectAttendee($id)
    {
        $attendee = Attendee::findOrFail($id);
        $attendee->update(['status' => 'rejected']);

        return back()->with('success', "Attendee {$attendee->full_name} rejected.");
    }

    public function approveAllForBooking($bookingId)
    {
        $booking = Booking::with('attendees')->findOrFail($bookingId);

        foreach ($booking->attendees as $attendee) {
            $attendee->update(['status' => 'approved']);
            $this->badgeService->generateForAttendee($attendee);
        }

        $booking->update(['attendee_status' => 'approved']);

        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'status' => 'Attendees Approved',
            'notes' => 'Admin approved all submitted attendees and generated badges.',
        ]);

        return back()->with('success', 'All attendees approved and badges generated.');
    }
}
