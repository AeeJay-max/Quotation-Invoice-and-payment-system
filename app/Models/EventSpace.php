<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSpace extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'code',
        'description',
        'location',
        'width',
        'length',
        'total_area_sqm',
        'available_area_sqm',
        'min_size',
        'max_size',
        'price_per_sqm',
        'fixed_price',
        'vip_tickets_quota',
        'vip_tickets_available',
        'vip_ticket_price',
        'general_tickets_quota',
        'general_tickets_available',
        'general_ticket_price',
        'delegate_tickets_quota',
        'delegate_tickets_available',
        'delegate_ticket_price',
        'availability_status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function positions()
    {
        return $this->hasMany(SpacePosition::class, 'event_space_id');
    }
}
