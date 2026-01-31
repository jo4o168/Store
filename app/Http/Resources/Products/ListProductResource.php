<?php

namespace App\Http\Resources\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**@var Product $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'active' => $this->is_active,
        ];
    }
}
