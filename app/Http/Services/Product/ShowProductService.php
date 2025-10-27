<?php

namespace App\Http\Services\Product;

use App\Models\Product;

class ShowProductService
{
    public function run(Product $product)
    {
        return $product->where('id', $product->id)->first();
    }
}
