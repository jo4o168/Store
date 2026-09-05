<?php

namespace App\Http\Services\Order;

use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\User;

class UpdateOrderStatusService
{
    public function run(Order $order, array $data, User $user): void
    {
        $user->loadMissing('profile');
        $profileId = (int) $user->profile->id;

        abort_unless(
            Order::query()->whereKey($order->id)->forProducer($profileId)->exists(),
            403
        );

        $statusKey = (string) $data['status'];
        $statusMap = [
            'pending' => OrderStatus::PENDING->value,
            'confirmed' => OrderStatus::CONFIRMED->value,
            'preparing' => OrderStatus::PREPARING->value,
            'shipped' => OrderStatus::SHIPPED->value,
            'delivered' => OrderStatus::DELIVERED->value,
            'cancelled' => OrderStatus::CANCELLED->value,
        ];

        $payload = [
            'status' => $statusMap[$statusKey],
        ];

        if ($statusKey === 'cancelled') {
            $payload['producer_message'] = trim((string) $data['producer_message']);
        } elseif ($statusKey === 'confirmed') {
            $message = trim((string) ($data['producer_message'] ?? ''));
            $payload['producer_message'] = $message !== ''
                ? $message
                : 'Seu pedido foi confirmado e está em preparação.';
        } elseif (! empty($data['producer_message'])) {
            $payload['producer_message'] = trim((string) $data['producer_message']);
        }

        $order->update($payload);
    }
}
