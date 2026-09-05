<?php

namespace App\Http\Services\Order;

use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StoreOrderService
{
    public function run(array $data, User $user): Order
    {
        $user->loadMissing('profile');
        $product = Product::query()->findOrFail((int) $data['product_id']);
        abort_unless($product->is_active, 422, 'Este kit de ovos não está disponível.');

        $isSubscription = ! empty($data['subscription_id']);
        if ($isSubscription) {
            abort_unless((bool) $product->allow_subscription, 422, 'Este kit não aceita assinatura.');
        } else {
            abort_unless((bool) $product->allow_one_time_purchase, 422, 'Este kit não aceita compra única.');
        }

        $quantity = max((int) ($data['quantity'] ?? 1), 1);
        $unitPrice = isset($data['unit_price'])
            ? (float) $data['unit_price']
            : (float) ($isSubscription
                ? ($product->subscription_price ?? $product->price)
                : ($product->one_time_price ?? $product->price));

        return DB::transaction(function () use ($data, $user, $product, $quantity, $unitPrice) {
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'delivery_address' => $data['delivery_address'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => OrderStatus::PENDING->value,
                'total_amount' => $unitPrice * $quantity,
                'customer_id' => $user->profile->id,
                'producer_id' => $product->producer_id,
                'subscription_id' => $data['subscription_id'] ?? null,
            ]);

            OrderItem::create([
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'order_id' => $order->id,
                'product_id' => $product->id,
            ]);

            return $order;
        });
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
