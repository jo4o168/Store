<?php

namespace App\Http\Services\Cart;

use App\Enum\CartPurchaseMode;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpsertCartItemService
{
    public function run(array $data, User $user): CartItem
    {
        $customerId = (int) $user->profile->id;
        $product = Product::query()->findOrFail((int) $data['product_id']);
        $mode = CartPurchaseMode::from($data['purchase_mode']);

        abort_unless($product->is_active, 422, 'Este kit de ovos não está disponível.');

        if ($mode === CartPurchaseMode::ONE_TIME) {
            abort_unless((bool) $product->allow_one_time_purchase, 422, 'Este kit não aceita compra única.');
            $planId = null;
            $quantity = max(1, (int) ($data['quantity'] ?? 1));
        } else {
            abort_unless((bool) $product->allow_subscription, 422, 'Este kit não aceita assinatura.');
            $planId = (int) ($data['subscription_plan_id'] ?? 0);
            if ($planId < 1) {
                throw ValidationException::withMessages(['subscription_plan_id' => ['Selecione um plano de assinatura.']]);
            }
            $plan = SubscriptionPlan::query()->findOrFail($planId);
            abort_unless($plan->is_active, 422, 'Este plano não está disponível.');
            abort_unless((int) $plan->producer_id === (int) $product->producer_id, 422, 'O plano não pertence a este produtor.');
            if ($plan->product_id) {
                abort_unless((int) $plan->product_id === (int) $product->id, 422, 'Este plano não pertence a este kit.');
            }
            $quantity = 1;
        }

        $existing = CartItem::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->where('purchase_mode', $mode->value)
            ->when(
                $planId === null,
                fn ($q) => $q->whereNull('subscription_plan_id'),
                fn ($q) => $q->where('subscription_plan_id', $planId),
            )
            ->first();

        if ($existing) {
            if ($mode === CartPurchaseMode::ONE_TIME) {
                $existing->quantity = min(999, $existing->quantity + $quantity);
            } else {
                $existing->quantity = 1;
                $existing->subscription_plan_id = $planId;
            }
            $existing->save();

            return $existing->load(['product', 'subscriptionPlan']);
        }

        return CartItem::create([
            'customer_id' => $customerId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'purchase_mode' => $mode->value,
            'subscription_plan_id' => $planId,
        ])->load(['product', 'subscriptionPlan']);
    }
}
