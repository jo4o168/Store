<?php

namespace App\Http\Services\Cart;

class CheckoutRequirements
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, list<string>>
     */
    public static function errors(array $data): array
    {
        $errors = [];
        $address = trim((string) ($data['delivery_address'] ?? ''));

        if ($address === '') {
            $errors['delivery_address'] = ['Informe o endereço completo de entrega.'];
        }

        $paymentMethodId = $data['payment_method_id'] ?? null;
        if ($paymentMethodId === null || $paymentMethodId === '' || (int) $paymentMethodId < 1) {
            $errors['payment_method_id'] = ['Selecione um método de pagamento para confirmar o pedido.'];
        }

        return $errors;
    }
}
