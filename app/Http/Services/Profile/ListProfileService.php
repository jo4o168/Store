<?php

namespace App\Http\Services\Profile;

use App\Http\Filters\Filter\DefaultFilter;
use App\Models\Profile;

class ListProfileService
{
    public function run(DefaultFilter $filter)
    {
        $model = new Profile();
        return $model->filterBy($filter)->get();
    }
}
