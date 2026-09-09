<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Event;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $selectedEventId = session('selected_event_id');
        $query = Vendor::with('event')->latest();

        if ($selectedEventId) {
            $query->where('event_id', $selectedEventId);
        }

        $vendors = $query->paginate(15);
        $events = Event::all();

        return view('admin.vendors.index', compact('vendors', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'company_name' => 'required|string|max:255',
            'service_category' => 'required|string',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'contract_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Vendor::create($validated);
        return back()->with('success', 'Service vendor contract registered.');
    }
}
