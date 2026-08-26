<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'company_name',
        'address',
        'phone',
        'email',
        'registration_number',
        'position',
        'mobile',
        'postal_address',
        'country',
        'website',
        'business_category'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'client_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }
}
