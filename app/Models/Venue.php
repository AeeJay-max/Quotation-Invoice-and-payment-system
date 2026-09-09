<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'city',
        'country',
        'contact_email',
        'contact_phone',
        'capacity',
        'status',
    ];

    public function halls()
    {
        return $this->hasMany(VenueHall::class, 'venue_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'venue_id');
    }
}
