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
}
