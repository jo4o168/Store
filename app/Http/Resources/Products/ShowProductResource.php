<?php

namespace App\Http\Resources\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**@var Product $this */

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'stock_quantity' => $this->stock_quantity,
            'unit' => $this->unit,
            'price' => $this->price,
            'active' => $this->is_active,
            'image_url' => $this->image_url,
            'producer' => [
                $this->producer->id,
                $this->producer->name
            ]
        ];
    }
}
