<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardPointTransaction extends Model
{
    protected $fillable = [
        'customer_id',
        'order_id',
        'type',
        'points',
        'description',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
