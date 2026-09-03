<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'birthday',
        'profile_photo',
        'city',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birthday' => 'date',
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('is_default', 1);
    }

    public function rewardPointTransactions()
    {
        return $this->hasMany(RewardPointTransaction::class);
    }

    public function totalRewardPoints(): int
    {
        $earned = $this->rewardPointTransactions()->where('type', 'earned')->sum('points');
        $spent = $this->rewardPointTransactions()->where('type', 'spent')->sum('points');
        $expired = $this->rewardPointTransactions()->where('type', 'expired')->sum('points');

        return (int) ($earned - $spent - $expired);
    }

    public static function rewardTiers(): array
    {
        return [
            ['name' => 'General', 'min' => 1000, 'max' => 2499],
            ['name' => 'Silver', 'min' => 2500, 'max' => 4999],
            ['name' => 'Silver Premium', 'min' => 5000, 'max' => 7499],
            ['name' => 'Gold', 'min' => 7500, 'max' => 10000],
        ];
    }

    public function currentTier(): ?string
    {
        $points = $this->totalRewardPoints();

        foreach (array_reverse(static::rewardTiers()) as $tier) {
            if ($points >= $tier['min']) {
                return $tier['name'];
            }
        }

        return null;
    }

    public function awardPointsForOrder(Order $order): void
    {
        $alreadyAwarded = $this->rewardPointTransactions()
            ->where('order_id', $order->id)
            ->where('type', 'earned')
            ->exists();

        if ($alreadyAwarded) {
            return;
        }

        $points = 0;
        foreach ($order->items as $item) {
            if ($item->product) {
                $points += $item->product->rewardPoints() * $item->quantity;
            }
        }

        if ($points > 0) {
            $this->rewardPointTransactions()->create([
                'order_id' => $order->id,
                'type' => 'earned',
                'points' => $points,
                'description' => 'Order #' . $order->order_number,
            ]);
        }
    }
    public function refundPointsForCancelledOrder(Order $order): void
    {
        $spent = $this->rewardPointTransactions()
            ->where('order_id', $order->id)
            ->where('type', 'spent')
            ->sum('points');

        if ($spent <= 0) {
            return;
        }

        // Avoid refunding twice if this fires more than once
        $alreadyRefunded = $this->rewardPointTransactions()
            ->where('order_id', $order->id)
            ->where('type', 'earned')
            ->where('description', 'like', 'Refund%')
            ->exists();

        if ($alreadyRefunded) {
            return;
        }

        $this->rewardPointTransactions()->create([
            'order_id' => $order->id,
            'type' => 'earned',
            'points' => $spent,
            'description' => 'Refund: Order #' . $order->order_number . ' cancelled',
        ]);
    }
}
