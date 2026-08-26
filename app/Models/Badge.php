<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendee_id',
        'booking_id',
        'badge_code',
        'qr_code_payload',
        'status',
        'generated_at',
        'printed_at'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'printed_at' => 'datetime',
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
