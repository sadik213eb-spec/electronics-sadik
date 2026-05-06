<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSectionItem extends Model
{
    protected $fillable = [
        'home_section_id',
        'type',
        'reference_id',
        'image',
        'link',
        'sort',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'reference_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'reference_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}
