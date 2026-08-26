<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'event_id',
        'booking_id',
        'quotation_id',
        'client_id',
        'user_id',
        'create_date',
        'due_date',
        'note',
        'terms_condition',
        'discount',
        'payment_type',
        'payment_currency',
        'payment_status',
        'send_interval',
        'send_date',
        'email_subject',
        'email_body',
        'attach',
        'schedule',
        'recurring',
        'sent',
        'is_scheduled',
        'vat',
        'total',
        'amount_paid',
        'amount_outstanding'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    protected $casts = [
        'send_date' => 'date',
        'create_date' => 'date',
        'due_date' => 'date',
        'is_schedule_sent' => 'boolean',
        'attach' => 'boolean',
        'is_scheduled' => 'boolean',
    ];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status');
    }

    public function paymentCurrency()
    {
        return $this->belongsTo(PaymentCurrency::class, 'payment_currency');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function emails(){
        return $this->hasMany(InvoiceEmail::class, 'invoice_id');
    }
}
