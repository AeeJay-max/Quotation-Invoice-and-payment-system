<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\VenueHall;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::withCount(['halls', 'events'])->latest()->paginate(15);
        return view('admin.venues.index', compact('venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['city'])) {
            $validated['city'] = 'Harare';
        }
        if (empty($validated['country'])) {
            $validated['country'] = 'Zimbabwe';
        }

        Venue::create($validated);
        return back()->with('success', 'Venue registered successfully.');
    }

    public function storeHall(Request $request, $venueId)
    {
        $venue = Venue::findOrFail($venueId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'building_name' => 'nullable|string',
            'floor_level' => 'nullable|string',
            'total_area_sqm' => 'nullable|numeric|min:0',
            'price_per_sqm' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:0',
            'vip_tickets_quota' => 'nullable|integer|min:0',
            'vip_ticket_price' => 'nullable|numeric|min:0',
            'general_tickets_quota' => 'nullable|integer|min:0',
            'general_ticket_price' => 'nullable|numeric|min:0',
            'delegate_tickets_quota' => 'nullable|integer|min:0',
            'delegate_ticket_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['venue_id'] = $venue->id;
        $validated['available_area_sqm'] = $validated['total_area_sqm'] ?? 0;
        $validated['vip_tickets_available'] = $validated['vip_tickets_quota'] ?? 0;
        $validated['general_tickets_available'] = $validated['general_tickets_quota'] ?? 0;
        $validated['delegate_tickets_available'] = $validated['delegate_tickets_quota'] ?? 0;

        VenueHall::create($validated);

        return back()->with('success', 'Venue hall/room added successfully with square meters, ticket quotas, and pricing.');
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        
        // Prevent deletion if there are associated events
        if ($venue->events()->count() > 0) {
            return back()->with('error', 'Cannot delete venue because it is associated with existing events.');
        }

        // Delete associated halls first
        $venue->halls()->delete();
        
        $venue->delete();

        return back()->with('success', 'Venue deleted successfully.');
    }
}
