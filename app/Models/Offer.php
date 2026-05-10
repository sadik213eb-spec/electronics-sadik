<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'seo_title',
        'seo_description',
        'description',
        'image',
        'banner',
        'product_ids',
        'show_timer',
        'show_on_page',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'show_timer'  => 'boolean',
        'show_on_page'  => 'boolean',
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
    ];

    //  Get products linked to this offer
    public function products()
    {
        if (empty($this->product_ids)) {
            return collect();
        }
        return Product::whereIn('id', $this->product_ids)
            ->where('status', 'active')
            ->get();
    }

    // Check if offer is active
    public function isActive(): bool
    {
        $now = now();
        return $this->status === 'active'
            && $now->gte($this->start_date)
            && $now->lte($this->end_date);
    }
}
