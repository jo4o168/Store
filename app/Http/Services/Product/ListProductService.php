<?php

namespace App\Http\Services\Product;

use App\Http\Filters\Filter\DefaultFilter;
use App\Models\Product;

class ListProductService
{
    public function run(DefaultFilter $filter)
    {
        $model = new Product();
        return $model->filterBy($filter)->get();
    }
}
