<?php

namespace Tests\Feature;

use App\Enum\EggColor;
use App\Enum\EggSize;
use App\Enum\ProfileRole;
use App\Models\Product;
use App\Models\Profile;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionPlanProductLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_product_is_created_without_subscription(): void
    {
        $producer = $this->createAccount(ProfileRole::PRODUCER, 'prod@example.com');
        Sanctum::actingAs($producer);

        $this->postJson('/api/products', [
            'name' => 'Kit avulso',
            'egg_size' => EggSize::MEDIUM->value,
            'egg_color' => EggColor::WHITE->value,
            'kit_quantity' => 12,
            'price' => 25,
            'one_time_price' => 25,
            'allow_subscription' => true,
            'subscription_price' => 20,
        ])->assertCreated();

        $product = Product::query()->firstOrFail();
        $this->assertFalse((bool) $product->allow_subscription);
        $this->assertTrue((bool) $product->allow_one_time_purchase);
        $this->assertNull($product->subscription_price);
    }

    public function test_plan_can_use_existing_product(): void
    {
        $producer = $this->createAccount(ProfileRole::PRODUCER, 'prod2@example.com');
        $product = Product::query()->create([
            'name' => 'Kit 30',
            'price' => 30,
            'one_time_price' => 30,
            'allow_one_time_purchase' => true,
            'allow_subscription' => false,
            'is_active' => true,
            'producer_id' => $producer->profile->id,
            'kit_quantity' => 30,
            'unit' => 0,
        ]);

        Sanctum::actingAs($producer);
        $this->postJson('/api/subscription-plans', [
            'name' => 'Semanal',
            'price' => 42,
            'frequency' => 0,
            'product_id' => $product->id,
        ])->assertNoContent();

        $plan = SubscriptionPlan::query()->firstOrFail();
        $this->assertSame($product->id, (int) $plan->product_id);
        $this->assertSame(30, (int) $plan->eggs_quantity);

        $product->refresh();
        $this->assertTrue((bool) $product->allow_subscription);
        $this->assertTrue((bool) $product->allow_one_time_purchase);
    }

    public function test_plan_can_create_subscription_only_product(): void
    {
        $producer = $this->createAccount(ProfileRole::PRODUCER, 'prod3@example.com');
        Sanctum::actingAs($producer);

        $this->postJson('/api/subscription-plans', [
            'name' => 'Mensal',
            'price' => 90,
            'frequency' => 2,
            'product' => [
                'name' => 'Kit assinatura',
                'egg_size' => EggSize::LARGE->value,
                'egg_color' => EggColor::RED->value,
                'kit_quantity' => 18,
            ],
        ])->assertNoContent();

        $product = Product::query()->firstOrFail();
        $this->assertTrue((bool) $product->allow_subscription);
        $this->assertFalse((bool) $product->allow_one_time_purchase);

        $plan = SubscriptionPlan::query()->firstOrFail();
        $this->assertSame($product->id, (int) $plan->product_id);
        $this->assertSame(18, (int) $plan->eggs_quantity);
    }

    public function test_plan_requires_product_or_new_kit(): void
    {
        $producer = $this->createAccount(ProfileRole::PRODUCER, 'prod4@example.com');
        Sanctum::actingAs($producer);

        $this->postJson('/api/subscription-plans', [
            'name' => 'Sem kit',
            'price' => 10,
        ])->assertUnprocessable();
    }

    private function createAccount(ProfileRole $role, string $email): User
    {
        $user = User::query()->create([
            'name' => 'Produtor',
            'username' => strstr($email, '@', true),
            'email' => $email,
            'password' => Hash::make('password'),
            'roles' => $role->value,
            'email_verified_at' => now(),
        ]);

        Profile::query()->create([
            'name' => $user->name,
            'role' => $role->value,
            'email' => $email,
            'user_id' => $user->id,
        ]);

        return $user->fresh(['profile']);
    }
}
