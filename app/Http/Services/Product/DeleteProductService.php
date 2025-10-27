<?php

namespace App\Http\Services\Product;

use App\Models\Product;

class DeleteProductService
{
    public function run(Product $product): void
    {
        $product->delete();
    }
}
