<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    use HasFactory;

    protected $table = 'furniture';

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'category',
        'unit_price',
        'vat',
        'available_quantity',
        'status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
