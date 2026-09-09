<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Client;
use App\Models\EventSession;
use App\Models\Speaker;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function show($id)
    {
        $event = Event::with([
            'spaces.positions',
            'standTypes',
            'furniture',
            'services',
            'sessions.speakers',
            'sponsors',
            'venueModel'
        ])->findOrFail($id);

        $confirmedExhibitors = Client::whereHas('bookings', function($q) use ($event) {
            $q->where('event_id', $event->id)->whereIn('status', ['confirmed', 'accepted', 'paid']);
        })->get();

        $bankDetails = \App\Models\Settings::where('type', 'email')->pluck('description', 'label')->toArray();
        $dbSettings = \App\Models\Settings::whereIn('type', ['system', 'general'])->pluck('description', 'label')->toArray();
        
        $global_settings = array_merge([
            'app_name' => 'Ministry of Sport, Recreation, Arts and Culture',
            'logo' => 'assets/files/ministry-logo.png',
        ], $dbSettings);

        return view('public.events.show', compact('event', 'confirmedExhibitors', 'bankDetails', 'global_settings'));
    }
}
