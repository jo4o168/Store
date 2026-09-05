<?php

namespace App\Http\Services\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

class ListOrderService
{
    public function run(User $user): Collection
    {
        $user->loadMissing('profile');
        $profileId = (int) $user->profile->id;

        $query = Order::query()->with([
            'items',
            'customer:id,name,email',
            'subscription.subscriptionPlan:id,name,producer_id',
        ]);

        if ($user->isProducer()) {
            $query->forProducer($profileId);
        } else {
            $query->where('customer_id', $profileId);
        }

        return $query->latest()->get();
    }
}
