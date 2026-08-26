<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'event_id',
        'client_id',
        'user_id',
        'quotation_id',
        'invoice_id',
        'event_space_id',
        'stand_type_id',
        'space_position_id',
        'width',
        'length',
        'area_sqm',
        'space_cost',
        'furniture_total',
        'services_total',
        'attendee_total',
        'subtotal',
        'discount',
        'vat_amount',
        'grand_total',
        'status',
        'payment_status',
        'attendee_status',
        'accepted_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function space()
    {
        return $this->belongsTo(EventSpace::class, 'event_space_id');
    }

    public function standType()
    {
        return $this->belongsTo(StandType::class, 'stand_type_id');
    }

    public function position()
    {
        return $this->belongsTo(SpacePosition::class, 'space_position_id');
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class, 'booking_id');
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'booking_id');
    }

    public function badges()
    {
        return $this->hasMany(Badge::class, 'booking_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(BookingStatusHistory::class, 'booking_id');
    }
}
