<?php

namespace App\Http\Services\Cart;

use App\Enum\CartPurchaseMode;
use App\Http\Services\Order\StoreOrderService;
use App\Http\Services\Subscription\StoreSubscriptionService;
use App\Models\CartItem;
use App\Models\PaymentMethod;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutCartService
{
    public function __construct(
        private readonly StoreOrderService $storeOrder,
        private readonly StoreSubscriptionService $storeSubscription,
    ) {}

    /**
     * @return array{order_ids: array<int>, subscription_ids: array<int>}
     */
    public function run(array $data, User $user): array
    {
        $requirementErrors = CheckoutRequirements::errors($data);
        if ($requirementErrors !== []) {
            throw ValidationException::withMessages($requirementErrors);
        }

        $customerId = (int) $user->profile->id;
        $pmId = isset($data['payment_method_id']) ? (int) $data['payment_method_id'] : null;

        if ($pmId) {
            $ok = PaymentMethod::query()
                ->where('id', $pmId)
                ->where('customer_id', $customerId)
                ->exists();
            abort_unless($ok, 422, 'Método de pagamento inválido.');
        }

        $items = CartItem::query()
            ->where('customer_id', $customerId)
            ->with(['product', 'subscriptionPlan'])
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => ['O carrinho está vazio.']]);
        }

        $orderIds = [];
        $subscriptionIds = [];

        DB::transaction(function () use ($items, $user, $data, $pmId, &$orderIds, &$subscriptionIds) {
            foreach ($items as $item) {
                $product = $item->product;
                abort_if(! $product, 422, 'Produto inválido no carrinho.');
                abort_unless($product->is_active, 422, 'Este kit de ovos não está disponível.');

                $mode = $item->purchase_mode instanceof CartPurchaseMode
                    ? $item->purchase_mode
                    : CartPurchaseMode::from((string) $item->purchase_mode);

                if ($mode === CartPurchaseMode::ONE_TIME) {
                    abort_unless((bool) $product->allow_one_time_purchase, 422, 'Este kit não aceita compra única.');
                    $order = $this->storeOrder->run([
                        'product_id' => $product->id,
                        'quantity' => max(1, (int) $item->quantity),
                        'delivery_address' => $data['delivery_address'] ?? null,
                        'notes' => $data['notes'] ?? null,
                    ], $user);
                    $orderIds[] = $order->id;
                } else {
                    abort_unless((bool) $product->allow_subscription, 422, 'Este kit não aceita assinatura.');
                    $plan = $item->subscriptionPlan;
                    if (! $plan && $item->subscription_plan_id) {
                        $plan = SubscriptionPlan::query()->find($item->subscription_plan_id);
                    }
                    abort_if(! $plan, 422, 'Plano de assinatura inválido no carrinho.');
                    abort_unless($plan->is_active, 422, 'Plano não disponível.');
                    abort_unless((int) $plan->producer_id === (int) $product->producer_id, 422, 'Plano incompatível com o produto.');
                    if ($plan->product_id) {
                        abort_unless((int) $plan->product_id === (int) $product->id, 422, 'Este plano não pertence a este kit.');
                    }

                    $sub = $this->storeSubscription->run([
                        'subscription_plan_id' => $plan->id,
                        'payment_method_id' => $pmId,
                    ], $user);
                    $subscriptionIds[] = $sub->id;

                    $order = $this->storeOrder->run([
                        'product_id' => $product->id,
                        'quantity' => max(1, (int) $item->quantity),
                        'delivery_address' => $data['delivery_address'] ?? null,
                        'notes' => $data['notes'] ?? null,
                        'subscription_id' => $sub->id,
                        'unit_price' => (float) $plan->price,
                    ], $user);
                    $orderIds[] = $order->id;
                }
            }

            CartItem::query()->where('customer_id', $user->profile->id)->delete();
        });

        return [
            'order_ids' => $orderIds,
            'subscription_ids' => $subscriptionIds,
        ];
    }
}
