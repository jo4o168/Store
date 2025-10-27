<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;

class StoreProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string'],
            'sku' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:product_categories,id'],
        ];
    }
}
