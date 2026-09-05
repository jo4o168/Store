<?php

namespace Tests\Feature;

use App\Enum\OrderStatus;
use App\Enum\ProfileRole;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderStatusUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancel_requires_producer_message(): void
    {
        [$producer, $order] = $this->makeProducerOrder();
        Sanctum::actingAs($producer);

        $this->putJson("/api/orders/{$order->id}", ['status' => 'cancelled'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['producer_message']);
    }

    public function test_producer_can_cancel_with_reason(): void
    {
        [$producer, $order] = $this->makeProducerOrder();
        Sanctum::actingAs($producer);

        $this->putJson("/api/orders/{$order->id}", [
            'status' => 'cancelled',
            'producer_message' => 'Estamos sem ovos vermelhos nesta semana.',
        ])->assertNoContent();

        $order->refresh();
        $this->assertSame(OrderStatus::CANCELLED->value, (int) $order->status);
        $this->assertSame('Estamos sem ovos vermelhos nesta semana.', $order->producer_message);
    }

    public function test_confirm_stores_customer_preparation_message(): void
    {
        [$producer, $order] = $this->makeProducerOrder();
        Sanctum::actingAs($producer);

        $this->putJson("/api/orders/{$order->id}", ['status' => 'confirmed'])
            ->assertNoContent();

        $order->refresh();
        $this->assertSame(OrderStatus::CONFIRMED->value, (int) $order->status);
        $this->assertSame('Seu pedido foi confirmado e está em preparação.', $order->producer_message);
    }

    /**
     * @return array{0: User, 1: Order}
     */
    private function makeProducerOrder(): array
    {
        $producer = User::query()->create([
            'name' => 'Produtor Pedidos',
            'email' => 'produtor-pedidos@test.com',
            'username' => 'produtor-pedidos@test.com',
            'password' => 'password',
            'roles' => [ProfileRole::PRODUCER->value],
            'email_verified_at' => now(),
            'active' => true,
        ]);

        $producerProfile = Profile::query()->create([
            'name' => 'Produtor Pedidos',
            'email' => 'produtor-pedidos@test.com',
            'role' => ProfileRole::PRODUCER->value,
            'user_id' => $producer->id,
        ]);

        $customer = User::query()->create([
            'name' => 'Cliente Pedidos',
            'email' => 'cliente-pedidos@test.com',
            'username' => 'cliente-pedidos@test.com',
            'password' => 'password',
            'roles' => [ProfileRole::CLIENT->value],
            'email_verified_at' => now(),
            'active' => true,
        ]);

        $customerProfile = Profile::query()->create([
            'name' => 'Cliente Pedidos',
            'email' => 'cliente-pedidos@test.com',
            'role' => ProfileRole::CLIENT->value,
            'user_id' => $customer->id,
        ]);

        $order = Order::query()->create([
            'order_number' => 'EGG-TEST-001',
            'status' => OrderStatus::PENDING->value,
            'total_amount' => 59.90,
            'customer_id' => $customerProfile->id,
            'producer_id' => $producerProfile->id,
        ]);

        return [$producer->fresh('profile'), $order];
    }
}
