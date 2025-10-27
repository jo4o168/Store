<?php

namespace App\Http\Requests\Product;

use App\Enum\ProductType;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'type' => ['required', Rule::enum(ProductType::class)],
            'stock' => ['required', 'integer'],
            'price' => ['sometimes', 'numeric'],
            'active' => ['sometimes', 'boolean'],
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
        ];
    }
}
