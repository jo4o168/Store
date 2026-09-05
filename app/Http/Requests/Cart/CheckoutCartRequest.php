<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class CheckoutCartRequest extends BaseRequest
{
    public function authorize(): bool
    {
        $roles = $this->user()?->roles;
        $role = is_array($roles) ? (int) ($roles[0] ?? 0) : (int) $roles;

        return $role === 0;
    }

    public function rules(): array
    {
        $customerId = $this->user()?->profile?->id;

        return [
            'delivery_address' => ['required', 'string', 'min:10', 'max:2000'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'payment_method_id' => [
                'required',
                'integer',
                Rule::exists('payment_methods', 'id')->where('customer_id', $customerId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'delivery_address.required' => 'Informe o endereço completo de entrega.',
            'delivery_address.min' => 'Informe o endereço completo de entrega, com rua, cidade e CEP.',
            'payment_method_id.required' => 'Selecione um método de pagamento para confirmar o pedido.',
            'payment_method_id.exists' => 'O método de pagamento selecionado é inválido.',
        ];
    }
}
