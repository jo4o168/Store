<?php

namespace Tests\Unit\Cart;

use App\Http\Services\Cart\CheckoutRequirements;
use PHPUnit\Framework\TestCase;

class CheckoutRequirementsTest extends TestCase
{
    public function test_accepts_address_and_payment(): void
    {
        $errors = CheckoutRequirements::errors([
            'delivery_address' => "Maria Silva\nRua das Flores, 123\nCampinas/SP — CEP 13010-000",
            'payment_method_id' => 3,
        ]);

        $this->assertSame([], $errors);
    }

    public function test_rejects_blank_delivery_address(): void
    {
        $errors = CheckoutRequirements::errors([
            'delivery_address' => '   ',
            'payment_method_id' => 1,
        ]);

        $this->assertArrayHasKey('delivery_address', $errors);
    }

    public function test_rejects_missing_payment_method(): void
    {
        $errors = CheckoutRequirements::errors([
            'delivery_address' => 'Rua das Flores, 123 — Campinas/SP',
        ]);

        $this->assertArrayHasKey('payment_method_id', $errors);
    }
}
