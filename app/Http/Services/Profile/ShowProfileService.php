<?php

namespace App\Http\Services\Profile;

use App\Models\Profile;

class ShowProfileService
{
    public function run(Profile $profile)
    {
        return $profile->where('id', $profile->id)->first();
    }
}
