<?php

namespace App\Http\Requests\Product;

use App\Enum\ProductType;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', Rule::enum(ProductType::class)],
            'stock' => ['sometimes', 'integer'],
            'price' => ['sometimes', 'numeric'],
            'active' => ['sometimes', 'boolean'],
            'contact_id' => ['sometimes', 'integer', 'exists:contacts,id'],
        ];
    }
}
