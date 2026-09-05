<?php

namespace App\Http\Requests\SubscriptionPlan;

use App\Enum\SubscriptionFrequency;
use App\Http\Requests\BaseRequest;
use App\Http\Services\SubscriptionPlan\UniqueProductFrequencyChecker;
use App\Models\SubscriptionPlan;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateSubscriptionPlanRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'eggs_quantity' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'frequency' => ['sometimes', 'integer', Rule::enum(SubscriptionFrequency::class)],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'product_id' => ['sometimes', 'nullable', 'integer', Rule::exists('products', 'id')],
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
            'image' => 'imagem',
            'image_url' => 'imagem',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $plan = $this->route('subscription_plan');
            if (! $plan instanceof SubscriptionPlan) {
                return;
            }

            $productId = $this->input('product_id', $plan->product_id);
            if (! $productId) {
                return;
            }

            $frequency = (int) ($this->input('frequency', $plan->frequency) ?? 0);
            $checker = app(UniqueProductFrequencyChecker::class);

            if ($checker->conflicts((int) $productId, $frequency, (int) $plan->id)) {
                $validator->errors()->add('frequency', $checker->message());
            }
        });
    }
}
