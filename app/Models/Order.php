<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'total_amount',
        'status',
        'discount_code',
        'discount_percentage',
        'verification_token',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if the order can be cancelled.
     * Only pending orders can be cancelled. Once completed (via email verification), they cannot be cancelled.
     */
    public function canBeCancelled(): bool
    {
        // Only pending orders can be cancelled
        return $this->status === 'pending';
    }
    
    /**
     * Get the display status for the order.
     */
    public function getDisplayStatus(): string
    {
        switch ($this->status) {
            case 'pending':
                return 'Pending';
            case 'completed':
                return 'Completed';
            case 'cancelled':
                return 'Cancelled';
            default:
                return ucfirst($this->status);
        }
    }
    
    /**
     * Get the CSS classes for the status badge.
     */
    public function getStatusBadgeClasses(): string
    {
        switch ($this->status) {
            case 'completed':
                return 'bg-green-100 text-green-800';
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}
