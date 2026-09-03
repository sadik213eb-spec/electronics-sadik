<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'sold_by',
        'price',
        'sale_price',
        'stock',
        'stock_status',
        'sku',
        'images',
        'short_description',
        'description',
        'warranty',
        'is_featured',
        'status',
        'shipping_inside_dhaka',
        'shipping_outside_dhaka',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Reward points earned when purchasing this product.
     * Calculated as a percentage of the effective (sale) price.
     */
    public function rewardPoints(): int
    {
        $price = $this->sale_price ?? $this->price;
        $rate = 0.005; // 0.5% of price — adjust as needed

        return (int) floor($price * $rate);
    }
    public function bestAutoDiscountPerUnit(): float
    {
        $price = $this->sale_price ?? $this->price;

        $offers = \App\Models\CheckoutOffer::currentlyValid()->get();

        $best = 0;

        foreach ($offers as $offer) {
            if ($offer->appliesToProduct($this->id)) {
                $best = max($best, $offer->discountPerUnit($price));
            }
        }

        return $best;
    }
}

