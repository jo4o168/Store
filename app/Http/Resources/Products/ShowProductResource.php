<?php

namespace App\Http\Resources\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**@var Product $data */
        $data = $this;

        return [
            'id' => $data->id,
            'name' => $data->name,
            'description' => $data->description,
            'type' => $data->type,
            'stock' => $data->stock,
            'price' => $data->price,
            'active' => $data->active,
            'contact' => [
                $data->contact->id,
                $data->contact->full_name
            ]
        ];
    }
}
