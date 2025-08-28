<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\BaseRequest;

class StoreProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'sku' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }
}
