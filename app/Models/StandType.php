<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'base_price',
        'pricing_rules',
        'status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
