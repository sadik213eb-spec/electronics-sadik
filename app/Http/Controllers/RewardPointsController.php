<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewardPointsController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth('customer')->user();

        $recentActivity = $customer->rewardPointTransactions()
            ->latest()
            ->take(10)
            ->get();

        $lastUpdated = $customer->rewardPointTransactions()->latest()->first()?->created_at;

        return view('account.reward-points', [
            'customer' => $customer,
            'points' => $customer->totalRewardPoints(),
            'tiers' => $customer::rewardTiers(),
            'currentTier' => $customer->currentTier(),
            'recentActivity' => $recentActivity,
            'lastUpdated' => $lastUpdated,
        ]);
    }
}
