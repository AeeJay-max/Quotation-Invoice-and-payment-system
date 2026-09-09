<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\Event;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        $selectedEventId = session('selected_event_id');
        $query = Sponsor::with('event')->latest();

        if ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        }

        $sponsors = $query->paginate(15);
        $events = Event::all();

        return view('admin.sponsors.index', compact('sponsors', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'sponsor_package' => 'required|string',
            'contribution_amount' => 'required|numeric|min:0',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
            'benefits_summary' => 'nullable|string',
        ]);

        Sponsor::create($validated);
        return back()->with('success', 'Sponsor agreement registered successfully.');
    }
}
