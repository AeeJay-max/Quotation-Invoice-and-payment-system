<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $selectedEventId = session('selected_event_id');
        $query = Ticket::with(['event', 'attendee', 'user'])->latest();

        if ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        }

        $tickets = $query->paginate(20);
        $events = Event::all();

        return view('admin.tickets.index', compact('tickets', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'attendee_id' => 'nullable|exists:attendees,id',
            'ticket_type' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $ticketNumber = 'TCK-' . strtoupper(Str::random(8));
        $event = Event::findOrFail($validated['event_id']);
        $attendee = $validated['attendee_id'] ? Attendee::find($validated['attendee_id']) : null;

        $payload = json_encode([
            'ticket_number' => $ticketNumber,
            'event' => $event->name,
            'event_id' => $event->id,
            'ticket_type' => $validated['ticket_type'],
            'attendee' => $attendee ? $attendee->full_name : 'General Attendee',
        ]);

        Ticket::create([
            'ticket_number' => $ticketNumber,
            'event_id' => $event->id,
            'attendee_id' => $validated['attendee_id'] ?? null,
            'ticket_type' => $validated['ticket_type'],
            'price' => $validated['price'],
            'qr_code_payload' => $payload,
            'status' => 'ISSUED',
        ]);

        return back()->with('success', 'Ticket issued successfully.');
    }
}
