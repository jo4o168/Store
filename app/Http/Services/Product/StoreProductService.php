<?php

namespace App\Http\Services\Product;

use App\Models\Product;

class StoreProductService
{
    public function run(array $data): array
    {
        return Product::create($data);
    }
}
