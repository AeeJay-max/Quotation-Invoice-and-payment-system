<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'description',
        'status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
