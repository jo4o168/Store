<?php

namespace Tests\Feature;

use App\Enum\ProfileRole;
use App\Models\Product;
use App\Models\Profile;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionPlanStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_producer_cannot_create_two_plans_with_same_product_and_frequency(): void
    {
        [$user, $product] = $this->makeProducerWithProduct();
        Sanctum::actingAs($user);

        $this->postJson('/api/subscription-plans', $this->planPayload($product->id, 0, 'Plano Semanal'))
            ->assertNoContent();

        $this->postJson('/api/subscription-plans', $this->planPayload($product->id, 0, 'Outro Semanal'))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['frequency']);

        $this->assertSame(1, SubscriptionPlan::query()->count());
    }

    public function test_producer_can_create_same_product_with_different_frequency(): void
    {
        [$user, $product] = $this->makeProducerWithProduct();
        Sanctum::actingAs($user);

        $this->postJson('/api/subscription-plans', $this->planPayload($product->id, 0, 'Plano Semanal'))
            ->assertNoContent();

        $this->postJson('/api/subscription-plans', $this->planPayload($product->id, 2, 'Plano Mensal'))
            ->assertNoContent();

        $this->assertSame(2, SubscriptionPlan::query()->count());
    }

    public function test_producer_can_recreate_frequency_after_deleting_plan(): void
    {
        [$user, $product] = $this->makeProducerWithProduct();
        Sanctum::actingAs($user);

        $this->postJson('/api/subscription-plans', $this->planPayload($product->id, 0, 'Plano Semanal'))
            ->assertNoContent();

        $plan = SubscriptionPlan::query()->firstOrFail();
        $this->deleteJson("/api/subscription-plans/{$plan->id}")->assertNoContent();

        $this->postJson('/api/subscription-plans', $this->planPayload($product->id, 0, 'Novo Semanal'))
            ->assertNoContent();

        $this->assertSame(1, SubscriptionPlan::query()->count());
    }

    public function test_producer_can_attach_image_to_plan(): void
    {
        Storage::fake('public');
        [$user, $product] = $this->makeProducerWithProduct();
        Sanctum::actingAs($user);

        $this->post('/api/subscription-plans', [
            ...$this->planPayload($product->id, 1, 'Plano com foto'),
            'image' => UploadedFile::fake()->image('plano.jpg'),
        ], ['Accept' => 'application/json'])->assertNoContent();

        $plan = SubscriptionPlan::query()->firstOrFail();
        $this->assertNotEmpty($plan->image_url);
        $this->assertStringContainsString('/storage/subscription-plans/', $plan->image_url);
    }

    /**
     * @return array{0: User, 1: Product}
     */
    private function makeProducerWithProduct(): array
    {
        $user = User::query()->create([
            'name' => 'Produtor Teste',
            'email' => 'produtor-plan@test.com',
            'username' => 'produtor-plan@test.com',
            'password' => 'password',
            'roles' => [ProfileRole::PRODUCER->value],
            'email_verified_at' => now(),
            'active' => true,
        ]);

        $profile = Profile::query()->create([
            'name' => 'Produtor Teste',
            'email' => 'produtor-plan@test.com',
            'role' => ProfileRole::PRODUCER->value,
            'user_id' => $user->id,
        ]);

        $product = Product::query()->create([
            'name' => 'Kit 30 ovos',
            'price' => 50,
            'egg_size' => 'Médio',
            'egg_color' => 'Branco',
            'kit_quantity' => 30,
            'allow_one_time_purchase' => true,
            'allow_subscription' => false,
            'is_active' => true,
            'producer_id' => $profile->id,
        ]);

        return [$user->fresh('profile'), $product];
    }

    /**
     * @return array<string, mixed>
     */
    private function planPayload(int $productId, int $frequency, string $name): array
    {
        return [
            'name' => $name,
            'description' => 'Plano de teste',
            'price' => 79.90,
            'frequency' => $frequency,
            'product_id' => $productId,
        ];
    }
}
