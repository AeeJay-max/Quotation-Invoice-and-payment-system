<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'item_type',
        'item_id',
        'name',
        'quantity',
        'unit_price',
        'total_price'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
