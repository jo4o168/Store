<?php

namespace App\Http\Services\SubscriptionPlan;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StorePlanImageService
{
    public function store(UploadedFile $image): string
    {
        $path = $image->store('subscription-plans', 'public');

        return url(Storage::disk('public')->url($path));
    }

    public function delete(?string $imageUrl): void
    {
        if (! $imageUrl) {
            return;
        }

        $publicPrefix = '/storage/';
        $position = strpos($imageUrl, $publicPrefix);

        if ($position === false) {
            return;
        }

        $path = substr($imageUrl, $position + strlen($publicPrefix));
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
