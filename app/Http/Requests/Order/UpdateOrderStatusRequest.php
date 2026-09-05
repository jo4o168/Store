<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseRequest;

class UpdateOrderStatusRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:pending,confirmed,preparing,shipped,delivered,cancelled'],
            'producer_message' => [
                'required_if:status,cancelled',
                'nullable',
                'string',
                'min:5',
                'max:1000',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'status',
            'producer_message' => 'observação para o cliente',
        ];
    }

    public function messages(): array
    {
        return [
            'producer_message.required_if' => 'Informe o motivo do cancelamento para o cliente.',
            'producer_message.min' => 'A observação precisa ter pelo menos 5 caracteres.',
        ];
    }
}
