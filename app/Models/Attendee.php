<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'attendee_type_id',
        'first_name',
        'last_name',
        'title',
        'email',
        'phone',
        'company',
        'position',
        'id_passport',
        'photo_path',
        'special_requirements',
        'status'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function attendeeType()
    {
        return $this->belongsTo(AttendeeType::class, 'attendee_type_id');
    }

    public function badge()
    {
        return $this->hasOne(Badge::class, 'attendee_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
