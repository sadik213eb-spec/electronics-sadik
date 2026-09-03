<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutOffer extends Model
{
    protected $fillable = [
        'name',
        'type',
        'amount',
        'product_ids',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Currently valid: active AND within its date range.
     */
    public function scopeCurrentlyValid($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Does this offer apply to the given product?
     * Empty/null product_ids means "applies to all products".
     */
    public function appliesToProduct(int $productId): bool
    {
        if (empty($this->product_ids)) {
            return true;
        }

        return in_array($productId, $this->product_ids);
    }

    /**
     * Discount amount for a single unit at the given price.
     */
    public function discountPerUnit(float $price): float
    {
        if ($this->type === 'percentage') {
            return round($price * ($this->amount / 100), 2);
        }

        return min((float) $this->amount, $price);
    }
}
