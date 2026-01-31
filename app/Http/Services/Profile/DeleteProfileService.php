<?php

namespace App\Http\Services\Profile;

use App\Models\Profile;

class DeleteProfileService
{
    public function run(Profile $profile): void
    {
        $profile->delete();
    }
}
