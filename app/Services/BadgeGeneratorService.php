<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Attendee;
use Illuminate\Support\Str;

class BadgeGeneratorService
{
    /**
     * Generate or fetch a badge for an attendee.
     */
    public function generateForAttendee(Attendee $attendee)
    {
        if ($attendee->badge) {
            return $attendee->badge;
        }

        $badgeCode = 'BDG-' . strtoupper(Str::random(8));
        $payload = json_encode([
            'badge_code' => $badgeCode,
            'attendee_id' => $attendee->id,
            'name' => $attendee->full_name,
            'company' => $attendee->company ?? ($attendee->booking->client->company_name ?? ''),
            'booking_number' => $attendee->booking->booking_number ?? '',
            'event' => $attendee->booking->event->name ?? '',
        ]);

        return Badge::create([
            'attendee_id' => $attendee->id,
            'booking_id' => $attendee->booking_id,
            'badge_code' => $badgeCode,
            'qr_code_payload' => $payload,
            'status' => 'generated',
            'generated_at' => now(),
        ]);
    }
}
