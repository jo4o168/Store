<?php

namespace App\Http\Services\Profile;

use App\Models\Profile;

class UpdateProfileService
{
    public function run(array $data, Profile $profile): void
    {
        $profile->fill($data);
        $profile->save();
    }
}
