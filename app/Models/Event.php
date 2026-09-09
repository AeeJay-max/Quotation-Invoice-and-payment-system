<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'event_code',
        'event_type',
        'venue_id',
        'description',
        'start_date',
        'end_date',
        'registration_open_date',
        'registration_close_date',
        'registration_start',
        'registration_end',
        'venue',
        'address',
        'country',
        'currency',
        'vat_rate',
        'status',
        'enabled_modules',
        'expected_attendance',
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
        'registration_start' => 'date',
        'registration_end' => 'date',
        'enabled_modules' => 'array',
    ];

    /**
     * Check if a specific module is enabled for this event.
     * Default modules if null: exhibition, commercial, attendees, badges.
     */
    public function hasModule(string $module): bool
    {
        if (empty($this->enabled_modules)) {
            return in_array($module, ['exhibition', 'commercial', 'attendees', 'badges', 'programme', 'tickets']);
        }
        return in_array($module, $this->enabled_modules);
    }

    public function venueModel()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

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

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'event_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'event_id');
    }

    public function sessions()
    {
        return $this->hasMany(EventSession::class, 'event_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id');
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class, 'event_id');
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class, 'event_id');
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'event_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'event_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'event_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'event_id');
    }
}
