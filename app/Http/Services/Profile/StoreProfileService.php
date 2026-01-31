<?php

namespace App\Http\Services\Profile;

use App\Models\Profile;

class StoreProfileService
{
    public function run(array $data): void
    {
        Profile::create($data);
    }
}
