<?php

namespace App\Http\Services\SubscriptionPlan;

use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class UpdateSubscriptionPlanService
{
    public function __construct(
        private readonly StorePlanImageService $storeImage,
        private readonly UniqueProductFrequencyChecker $frequencyChecker,
    ) {}

    public function run(array $data, SubscriptionPlan $subscriptionPlan, User $user): void
    {
        $user->loadMissing('profile');
        abort_unless((int) $subscriptionPlan->producer_id === (int) $user->profile->id, 403);

        $image = $data['image'] ?? null;
        unset($data['image']);

        if (array_key_exists('product_id', $data) && $data['product_id']) {
            $product = Product::query()->findOrFail((int) $data['product_id']);
            abort_unless((int) $product->producer_id === (int) $user->profile->id, 403, 'Este kit não pertence a você.');
            $product->allow_subscription = true;
            if (isset($data['price'])) {
                $product->subscription_price = $data['price'];
            }
            $product->save();
            $data['eggs_quantity'] = $data['eggs_quantity'] ?? $product->kit_quantity;
        }

        $productId = (int) ($data['product_id'] ?? $subscriptionPlan->product_id);
        $frequency = (int) ($data['frequency'] ?? $subscriptionPlan->frequency);

        if ($productId && $this->frequencyChecker->conflicts($productId, $frequency, (int) $subscriptionPlan->id)) {
            throw ValidationException::withMessages([
                'frequency' => [$this->frequencyChecker->message()],
            ]);
        }

        if ($image instanceof UploadedFile) {
            $this->storeImage->delete($subscriptionPlan->image_url);
            $data['image_url'] = $this->storeImage->store($image);
        }

        unset($data['product']);
        $subscriptionPlan->fill($data);
        $subscriptionPlan->save();
    }
}
