<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::withCount('sessions')->latest()->paginate(15);
        return view('admin.speakers.index', compact('speakers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);

        Speaker::create($validated);
        return back()->with('success', 'Speaker profile added successfully.');
    }
}
