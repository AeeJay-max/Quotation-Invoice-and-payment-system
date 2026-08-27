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
        // amount = what exhibitor submitted (kept for backward compat with existing records)
        'amount',
        // amount_claimed = authoritative "what exhibitor claims they paid"
        'amount_claimed',
        // amount_verified = what admin actually approved — null until verified
        'amount_verified',
        'currency',
        'payment_method',
        'transaction_reference',
        'proof_of_payment_path',
        'payment_date',
        'status',
        'notes',
        // Audit trail
        'verified_by',
        'verified_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at'  => 'datetime',
        'rejected_at'  => 'datetime',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

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

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * The authoritative claimed amount.
     * Falls back to `amount` for backward compatibility with pre-migration records.
     */
    public function getClaimedAmountAttribute(): float
    {
        return floatval($this->amount_claimed ?? $this->amount ?? 0);
    }

    /**
     * Status label for display.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'verified' => 'Payment Verified',
            'rejected' => 'Payment Rejected',
            default    => 'Pending Admin Verification',
        };
    }

    /**
     * Bootstrap to keep amount and amount_claimed in sync.
     */
    protected static function booted()
    {
        static::creating(function (Payment $payment) {
            // Sync amount_claimed ← amount when amount_claimed not set
            if ($payment->amount && !$payment->amount_claimed) {
                $payment->amount_claimed = $payment->amount;
            }
            // Sync amount ← amount_claimed for backward compat
            if ($payment->amount_claimed && !$payment->amount) {
                $payment->amount = $payment->amount_claimed;
            }
        });
    }
}
