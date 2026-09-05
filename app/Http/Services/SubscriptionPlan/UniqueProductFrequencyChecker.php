<?php

namespace App\Http\Services\SubscriptionPlan;

use App\Models\SubscriptionPlan;

class UniqueProductFrequencyChecker
{
    public function conflicts(int $productId, int $frequency, ?int $ignorePlanId = null): bool
    {
        return SubscriptionPlan::query()
            ->where('product_id', $productId)
            ->where('frequency', $frequency)
            ->when($ignorePlanId, fn ($query) => $query->where('id', '!=', $ignorePlanId))
            ->exists();
    }

    public function message(): string
    {
        return 'Já existe um plano com esta periodicidade para este kit. Escolha outra frequência.';
    }
}
