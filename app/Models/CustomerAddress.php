<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = ['customer_id', 'type', 'name', 'mobile', 'address', 'city', 'is_default'];
}
