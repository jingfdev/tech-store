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
     */
    public function canBeCancelled(): bool
    {
        // Can't cancel already cancelled orders
        if ($this->status === 'cancelled') {
            return false;
        }
        
        // Allow cancellation of pending orders anytime
        if ($this->status === 'pending') {
            return true;
        }
        
        // Allow cancellation of completed orders within 24 hours
        if ($this->status === 'completed') {
            return $this->created_at->diffInHours(now()) <= 24;
        }
        
        return false;
    }
}
