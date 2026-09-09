<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Event;

class SetSelectedEventMiddleware
{
    /**
     * Handle an incoming request.
     * Sets active event in session for admin navigation context.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->role_id == 1 || auth()->user()->is_admin)) {
            if ($request->has('set_event_id')) {
                $eventId = $request->get('set_event_id');
                if ($eventId === 'all') {
                    session()->forget('selected_event_id');
                } else {
                    $event = Event::find($eventId);
                    if ($event) {
                        session(['selected_event_id' => $event->id]);
                    }
                }
            }

            // Default to latest open/published event if none selected
            if (!session()->has('selected_event_id') && !session()->has('global_mode')) {
                $latestEvent = Event::latest()->first();
                if ($latestEvent) {
                    session(['selected_event_id' => $latestEvent->id]);
                }
            }
        }

        return $next($request);
    }
}
