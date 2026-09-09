<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Ticket;
use App\Models\Badge;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function index(Request $request)
    {
        $selectedEventId = session('selected_event_id');
        $query = Checkin::with(['event', 'ticket', 'badge.attendee', 'checkedInBy'])->latest();

        if ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        }

        $checkins = $query->paginate(30);
        $events = Event::all();

        return view('admin.checkin.index', compact('checkins', 'events'));
    }

    public function scanner()
    {
        $activeEvent = session('selected_event_id') ? Event::find(session('selected_event_id')) : Event::latest()->first();
        return view('admin.checkin.scanner', compact('activeEvent'));
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'station_name' => 'nullable|string',
        ]);

        $code = trim($validated['qr_code']);
        $eventId = $validated['event_id'];

        // Attempt ticket match first (TCK-...) or badge match (BDG-...)
        $ticket = Ticket::where('ticket_number', $code)->orWhere('qr_code_payload', 'LIKE', "%{$code}%")->first();
        $badge = null;

        if (!$ticket) {
            $badge = Badge::where('badge_code', $code)->orWhere('qr_code_payload', 'LIKE', "%{$code}%")->first();
        }

        if (!$ticket && !$badge) {
            return response()->json([
                'success' => false,
                'message' => 'INVALID QR CODE: No matching ticket or badge found.',
            ], 404);
        }

        // Event validation
        $targetEventId = $ticket ? $ticket->event_id : ($badge->booking ? $badge->booking->event_id : null);
        if ($targetEventId != $eventId) {
            return response()->json([
                'success' => false,
                'message' => 'EVENT MISMATCH: This pass is issued for a different event!',
            ], 422);
        }

        // Duplicate check-in verification
        $alreadyCheckedIn = Checkin::where('event_id', $eventId)
            ->where(function ($q) use ($ticket, $badge) {
                if ($ticket) $q->where('ticket_id', $ticket->id);
                if ($badge) $q->orWhere('badge_id', $badge->id);
            })->exists();

        if ($alreadyCheckedIn && !$request->has('allow_reentry')) {
            return response()->json([
                'success' => false,
                'already_checked_in' => true,
                'message' => 'ALREADY CHECKED IN: Participant has already completed entrance check-in.',
            ], 409);
        }

        // Log successful check-in
        $checkin = Checkin::create([
            'event_id' => $eventId,
            'ticket_id' => $ticket ? $ticket->id : null,
            'badge_id' => $badge ? $badge->id : null,
            'attendee_id' => $ticket ? $ticket->attendee_id : ($badge ? $badge->attendee_id : null),
            'station_name' => $validated['station_name'] ?? 'Main Entrance',
            'checked_in_by' => Auth::id(),
            'checkin_time' => now(),
        ]);

        if ($ticket) {
            $ticket->update(['status' => 'USED', 'used_at' => now()]);
        }

        $participantName = $ticket
            ? (optional($ticket->attendee)->full_name ?? 'Ticket Holder')
            : (optional($badge->attendee)->full_name ?? 'Badge Holder');

        return response()->json([
            'success' => true,
            'message' => "CHECK-IN SUCCESSFUL! Welcome {$participantName}.",
            'participant' => $participantName,
            'checkin_time' => $checkin->checkin_time->format('Y-m-d H:i:s'),
        ]);
    }
}
