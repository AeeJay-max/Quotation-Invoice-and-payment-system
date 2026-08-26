<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'booking_id',
        'quotation_id',
        'quotation_number',
        'client_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_reference',
        'proof_of_payment_path',
        'payment_date',
        'status',
        'notes'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
