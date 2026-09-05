<?php

namespace App\Http\Services\Stats;

use App\Enum\OrderStatus;
use App\Enum\SubscriptionStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;

class GetProducerStatsService
{
    public function run(User $user): array
    {
        $user->loadMissing('profile');
        $profileId = (int) $user->profile->id;

        $productsCount = Product::query()
            ->where('producer_id', $profileId)
            ->where('is_active', true)
            ->count();

        $producerOrders = Order::query()->forProducer($profileId);

        $pendingOrdersCount = (clone $producerOrders)
            ->where('status', OrderStatus::PENDING->value)
            ->count();

        $totalRevenue = (float) (clone $producerOrders)
            ->where('status', OrderStatus::DELIVERED->value)
            ->sum('total_amount');

        $subscribersCount = Subscription::query()
            ->whereHas('subscriptionPlan', fn ($q) => $q->where('producer_id', $profileId))
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->count();

        return [
            'productsCount' => $productsCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'totalRevenue' => $totalRevenue,
            'subscribersCount' => $subscribersCount,
        ];
    }
}
