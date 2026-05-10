<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBanner extends Model
{
    protected $fillable = [
        'image',
        'link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
