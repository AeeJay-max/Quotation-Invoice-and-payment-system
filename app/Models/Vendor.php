<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'company_name',
        'service_category',
        'contact_person',
        'email',
        'phone',
        'contract_value',
        'payment_status',
        'status',
        'notes',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
