<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpacePosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_space_id',
        'position_number',
        'label',
        'position_type',
        'additional_fee',
        'status'
    ];

    public function space()
    {
        return $this->belongsTo(EventSpace::class, 'event_space_id');
    }
}
