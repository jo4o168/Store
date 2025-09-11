<?php

namespace App\Http\Services\Product;

use App\Models\Product;

class UpdateProductService
{
    public function run(array $data, Product $product): void
    {
        $product->fill($data);
        $product->save();
    }
}
