<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSpace;
use App\Models\StandType;
use App\Models\SpacePosition;
use App\Models\Furniture;
use App\Models\EventService;
use App\Models\AttendeeType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['spaces', 'standTypes', 'bookings'])->latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,published,registration_open,registration_closed,completed,cancelled',
            'terms_and_conditions' => 'nullable|string',
            'booking_guidelines' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ]);

        $validated['event_code'] = 'EVT-' . strtoupper(Str::random(6));

        $event = Event::create($validated);

        return redirect()->route('admin.events.manage', $event->id)
            ->with('success', 'Event created successfully. You can now configure spaces, stands, and catalogue items.');
    }

    public function manage($id)
    {
        $event = Event::with([
            'spaces.positions',
            'standTypes',
            'furniture',
            'services',
            'attendeeTypes'
        ])->findOrFail($id);

        return view('admin.events.manage', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'venue' => 'nullable|string',
            'currency' => 'required|string',
            'vat_rate' => 'required|numeric',
            'status' => 'required|in:draft,published,registration_open,registration_closed,completed,cancelled',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $event->update($validated);
        return back()->with('success', 'Event updated successfully.');
    }

    // --- Sub-entity Store Methods ---

    public function storeSpace(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'min_size' => 'required|numeric|min:1',
            'max_size' => 'required|numeric|gte:min_size',
            'price_per_sqm' => 'required|numeric|min:0',
            'fixed_price' => 'nullable|numeric|min:0',
        ]);

        $validated['event_id'] = $event->id;
        EventSpace::create($validated);

        return back()->with('success', 'Exhibition space added successfully.');
    }

    public function storeStandType(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
        ]);

        $validated['event_id'] = $event->id;
        StandType::create($validated);

        return back()->with('success', 'Stand type added successfully.');
    }

    public function storePosition(Request $request, $spaceId)
    {
        $space = EventSpace::findOrFail($spaceId);
        $validated = $request->validate([
            'position_number' => 'required|string|max:50',
            'position_type' => 'required|string',
            'additional_fee' => 'required|numeric|min:0',
        ]);

        $validated['event_space_id'] = $space->id;
        SpacePosition::create($validated);

        return back()->with('success', 'Position added successfully.');
    }

    public function storeFurniture(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'available_quantity' => 'required|integer|min:0',
        ]);

        $validated['event_id'] = $event->id;
        Furniture::create($validated);

        return back()->with('success', 'Furniture item added to catalogue.');
    }

    public function storeService(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['event_id'] = $event->id;
        EventService::create($validated);

        return back()->with('success', 'Event service added.');
    }

    public function storeAttendeeType(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['event_id'] = $event->id;
        AttendeeType::create($validated);

        return back()->with('success', 'Attendee ticket type added.');
    }
}
