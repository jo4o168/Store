<?php

namespace App\Http\Services\SubscriptionPlan;

use App\Http\Filters\Filter\DefaultFilter;
use App\Models\SubscriptionPlan;
use App\Models\User;

class ListSubscriptionPlanService
{
    public function run(DefaultFilter $filter, User $user)
    {
        $user->loadMissing('profile');
        $profileId = (int) $user->profile->id;

        return SubscriptionPlan::query()
            ->with(['product:id,name,kit_quantity,egg_size,egg_color,allow_one_time_purchase,image_url,producer_id'])
            ->when($user->isProducer(), fn ($q) => $q->where('producer_id', $profileId))
            ->latest()
            ->get();
    }
}
