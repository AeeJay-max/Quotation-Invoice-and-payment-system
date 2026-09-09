<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'company_name',
        'sponsor_package',
        'contribution_amount',
        'payment_status',
        'contact_person',
        'email',
        'phone',
        'website',
        'logo_path',
        'benefits_summary',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
