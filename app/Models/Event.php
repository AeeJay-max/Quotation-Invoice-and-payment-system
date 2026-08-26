<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_code',
        'description',
        'start_date',
        'end_date',
        'registration_open_date',
        'registration_close_date',
        'venue',
        'address',
        'country',
        'currency',
        'vat_rate',
        'status',
        'banner_path',
        'terms_and_conditions',
        'booking_guidelines',
        'contact_info'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_open_date' => 'date',
        'registration_close_date' => 'date',
    ];

    public function spaces()
    {
        return $this->hasMany(EventSpace::class, 'event_id');
    }

    public function standTypes()
    {
        return $this->hasMany(StandType::class, 'event_id');
    }

    public function furniture()
    {
        return $this->hasMany(Furniture::class, 'event_id');
    }

    public function services()
    {
        return $this->hasMany(EventService::class, 'event_id');
    }

    public function attendeeTypes()
    {
        return $this->hasMany(AttendeeType::class, 'event_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'event_id');
    }
}
