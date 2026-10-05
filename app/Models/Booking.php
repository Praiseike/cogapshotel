<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'room_id',
        'check_in',
        'check_out',
        'guests_count',
        'total_amount',
        'status',
        'payment_reference',
        'payment_id',
        'paid_at',
        'notes',
        'source',
    ];

    public const SOURCES = [
        'direct' => 'Direct / walk-in',
        'google' => 'Google search',
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'referral' => 'Friend / referral',
        'agent' => 'Travel agent',
        'other' => 'Other',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'guests_count' => 'integer',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->payment_reference)) {
                $booking->payment_reference = 'BOOK-'.Str::random(12);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withTrashed();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('check_in', '>=', now()->toDateString())
            ->whereIn('status', ['confirmed', 'pending_payment']);
    }

    public function scopePast($query)
    {
        return $query->where('check_out', '<', now()->toDateString());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'pending_payment']);
    }

    public function scopeForStatus($query, ?string $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function getNightsCount(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'pending_payment' => 'badge-warning',
            'confirmed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'completed' => 'badge-info',
            default => 'badge-gray',
        };
    }
}
