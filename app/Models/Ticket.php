<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'event_id',
        'attendee_id',
        'user_id',
        'ticket_type',
        'price',
        'qr_code_payload',
        'status',
        'issued_at',
        'used_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class, 'ticket_id');
    }
}
