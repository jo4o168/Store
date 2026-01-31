<?php

namespace App\Http\Requests\Product;

use App\Enum\UnitEggs;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'stock_quantity' => ['sometimes', 'integer'],
            'unit' => ['sometimes', 'integer', Rule::enum(UnitEggs::class)],
            'price' => ['required', 'numeric'],
            'is_active' => ['sometimes', 'boolean'],
            'image_url' => ['sometimes', 'nullable', 'string'],
            'producer_id' => ['required', 'integer', 'exists:profiles,id'],
        ];
    }
}
