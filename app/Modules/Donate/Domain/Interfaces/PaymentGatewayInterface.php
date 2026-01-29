<?php

namespace Modules\Donate\Domain\Interfaces;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function id(): string;
    public function displayName(): string;
    public function createCheckout(float $amount, array $meta = []): array;
    public function handleCallback(Request $request): array;
    public function handleWebhook(Request $request): array;
}

