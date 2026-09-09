<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'session_date',
        'start_time',
        'end_time',
        'venue_room',
        'max_capacity',
        'session_type',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'session_speakers', 'event_session_id', 'speaker_id')
            ->withPivot('role');
    }
}
