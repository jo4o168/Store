<?php

namespace App\Http\Services\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreProductService
{
    public function run(array $data, User $user): Product
    {
        $user->loadMissing('profile');
        $image = $data['image'] ?? null;
        unset($data['image']);

        if ($image instanceof UploadedFile) {
            $data['image_url'] = $this->storeImage($image);
        }

        $subscriptionOnly = (bool) ($data['subscription_only'] ?? false);
        unset($data['subscription_only'], $data['allow_subscription'], $data['allow_one_time_purchase']);

        if ($subscriptionOnly) {
            $data['allow_subscription'] = true;
            $data['allow_one_time_purchase'] = false;
            $data['subscription_price'] = $data['subscription_price'] ?? $data['price'] ?? null;
            $data['one_time_price'] = null;
            $data['price'] = (float) ($data['subscription_price'] ?? $data['price'] ?? 0);
        } else {
            $data['allow_subscription'] = false;
            $data['allow_one_time_purchase'] = true;
            $data['subscription_price'] = null;
            $data['one_time_price'] = $data['one_time_price'] ?? $data['price'] ?? null;
            $data['price'] = (float) ($data['one_time_price'] ?? $data['price'] ?? 0);
        }

        $data['producer_id'] = $user->profile->id;

        return Product::create($data);
    }

    private function storeImage(UploadedFile $image): string
    {
        $path = $image->store('products', 'public');
        return url(Storage::disk('public')->url($path));
    }
}
