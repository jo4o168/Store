<?php

namespace App\Http\Services\Order;

use App\Models\Order;
use App\Models\User;

class ShowOrderService
{
    public function run(Order $order, User $user): Order
    {
        $user->loadMissing('profile');
        $profileId = (int) $user->profile->id;

        if ($user->isClient()) {
            abort_unless((int) $order->customer_id === $profileId, 403);
        } else {
            abort_unless(
                Order::query()->whereKey($order->id)->forProducer($profileId)->exists(),
                403
            );
        }

        return $order->load([
            'items',
            'customer:id,name,email',
            'subscription.subscriptionPlan',
        ]);
    }
}
