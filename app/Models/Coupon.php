<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'amount',
        'product_ids',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'is_active'   => 'boolean',
        'amount'      => 'decimal:2',
    ];

    // ✅ Check if coupon is valid
    public function isValid(): bool
    {
        $now = now();
        return $this->is_active
            && $now->gte($this->start_date)
            && $now->lte($this->end_date);
    }

    // ✅ Check if coupon applies to a product
    public function appliesToProduct(int $productId): bool
    {
        // If no products set — applies to all
        if (empty($this->product_ids)) {
            return true;
        }
        return in_array($productId, $this->product_ids);
    }

    // ✅ Calculate discount for a given amount
    public function calculateDiscount(float $total): float
    {
        if ($this->type === 'percentage') {
            return round($total * ($this->amount / 100), 2);
        }
        return min($this->amount, $total);
    }
}
