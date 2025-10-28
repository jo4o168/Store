<?php

namespace App\Http\Resources\Products;

use App\Enum\ProductType;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class ListProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**@var Product $data */
        $data = $this;

        return [
            'id' => $data->id,
            'name' => $data->name,
            'type' => $data->type,
            'stock' => $data->stock,
            'price' => $data->price,
            'active' => $data->active,
        ];
    }
}
