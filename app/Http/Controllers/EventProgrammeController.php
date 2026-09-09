<?php

namespace App\Http\Controllers;

use App\Models\EventSession;
use App\Models\Speaker;
use App\Models\Event;
use Illuminate\Http\Request;

class EventProgrammeController extends Controller
{
    public function index(Request $request)
    {
        $selectedEventId = session('selected_event_id');
        $query = EventSession::with(['event', 'speakers'])->orderBy('session_date')->orderBy('start_time');

        if ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        }

        $sessions = $query->paginate(20);
        $speakers = Speaker::all();
        $events = Event::all();

        return view('admin.programme.index', compact('sessions', 'speakers', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'venue_room' => 'nullable|string',
            'max_capacity' => 'nullable|integer',
            'session_type' => 'required|string',
            'speakers' => 'nullable|array',
        ]);

        $session = EventSession::create($validated);

        if (!empty($validated['speakers'])) {
            $session->speakers()->sync($validated['speakers']);
        }

        return back()->with('success', 'Agenda session scheduled successfully.');
    }
}
