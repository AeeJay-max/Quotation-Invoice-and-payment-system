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
        'min_size',
        'max_size',
        'price_per_sqm',
        'fixed_price',
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
