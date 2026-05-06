<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'url',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(HomeSectionItem::class)->orderBy('sort');
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            HomeSectionItem::class,
            'home_section_id',
            'id',
            'id',
            'reference_id'
        );
    }

    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            HomeSectionItem::class,
            'home_section_id',
            'id',
            'id',
            'reference_id'
        );
    }
}
