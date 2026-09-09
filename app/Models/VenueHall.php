<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueHall extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'building_name',
        'floor_level',
        'total_area_sqm',
        'available_area_sqm',
        'price_per_sqm',
        'capacity',
        'vip_tickets_quota',
        'vip_tickets_available',
        'vip_ticket_price',
        'general_tickets_quota',
        'general_tickets_available',
        'general_ticket_price',
        'delegate_tickets_quota',
        'delegate_tickets_available',
        'delegate_ticket_price',
        'notes',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}
