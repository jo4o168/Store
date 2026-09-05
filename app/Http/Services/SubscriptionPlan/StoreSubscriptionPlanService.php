<?php

namespace App\Http\Services\SubscriptionPlan;

use App\Http\Services\Product\StoreProductService;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreSubscriptionPlanService
{
    public function __construct(
        private readonly StoreProductService $storeProduct,
        private readonly StorePlanImageService $storeImage,
        private readonly UniqueProductFrequencyChecker $frequencyChecker,
    ) {}

    public function run(array $data, User $user): SubscriptionPlan
    {
        $user->loadMissing('profile');
        $profileId = (int) $user->profile->id;
        $image = $data['image'] ?? null;
        unset($data['image']);

        return DB::transaction(function () use ($data, $user, $profileId, $image) {
            $product = $this->resolveProduct($data, $user, $profileId, (float) $data['price']);
            $frequency = (int) ($data['frequency'] ?? 0);

            if ($this->frequencyChecker->conflicts((int) $product->id, $frequency)) {
                throw ValidationException::withMessages([
                    'frequency' => [$this->frequencyChecker->message()],
                ]);
            }

            $imageUrl = $image instanceof UploadedFile ? $this->storeImage->store($image) : null;

            return SubscriptionPlan::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'image_url' => $imageUrl,
                'price' => $data['price'],
                'eggs_quantity' => (int) ($data['eggs_quantity'] ?? $product->kit_quantity ?? 1),
                'frequency' => $frequency,
                'is_active' => $data['is_active'] ?? true,
                'is_featured' => $data['is_featured'] ?? false,
                'producer_id' => $profileId,
                'product_id' => $product->id,
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveProduct(array $data, User $user, int $profileId, float $planPrice): Product
    {
        if (! empty($data['product']) && is_array($data['product'])) {
            return $this->storeProduct->run([
                ...$data['product'],
                'price' => $planPrice,
                'subscription_price' => $planPrice,
                'subscription_only' => true,
                'is_active' => true,
            ], $user);
        }

        $product = Product::query()->findOrFail((int) $data['product_id']);
        abort_unless((int) $product->producer_id === $profileId, 403, 'Este kit não pertence a você.');

        $product->allow_subscription = true;
        $product->subscription_price = $planPrice;
        $product->save();

        return $product;
    }
}
