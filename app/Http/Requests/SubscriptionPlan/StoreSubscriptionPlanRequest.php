<?php

namespace App\Http\Requests\SubscriptionPlan;

use App\Enum\EggColor;
use App\Enum\EggSize;
use App\Enum\SubscriptionFrequency;
use App\Http\Requests\BaseRequest;
use App\Http\Services\SubscriptionPlan\UniqueProductFrequencyChecker;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreSubscriptionPlanRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'eggs_quantity' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'frequency' => ['sometimes', 'integer', Rule::enum(SubscriptionFrequency::class)],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'product_id' => [
                'required_without:product',
                'nullable',
                'integer',
                Rule::exists('products', 'id'),
            ],
            'product' => ['required_without:product_id', 'nullable', 'array'],
            'product.name' => ['required_with:product', 'string'],
            'product.egg_size' => ['required_with:product', Rule::enum(EggSize::class)],
            'product.egg_color' => ['required_with:product', Rule::enum(EggColor::class)],
            'product.kit_quantity' => ['required_with:product', 'integer', 'min:1'],
            'product.description' => ['sometimes', 'nullable', 'string'],
            'image_url' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'file', 'max:5120', 'extensions:jpg,jpeg,png,webp,gif,bmp,svg,avif,jfif'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome do plano',
            'price' => 'preço',
            'frequency' => 'frequência',
            'product_id' => 'kit de ovos',
            'product' => 'novo kit',
            'product.name' => 'nome do kit',
            'product.egg_size' => 'tamanho do ovo',
            'product.egg_color' => 'cor do ovo',
            'product.kit_quantity' => 'quantidade por kit',
            'image' => 'imagem',
            'image_url' => 'imagem',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $productId = $this->input('product_id');
            if (! $productId) {
                return;
            }

            $checker = app(UniqueProductFrequencyChecker::class);
            $frequency = (int) ($this->input('frequency') ?? 0);

            if ($checker->conflicts((int) $productId, $frequency)) {
                $validator->errors()->add('frequency', $checker->message());
            }
        });
    }
}
