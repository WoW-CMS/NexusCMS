<?php

namespace Modules\Donate\Services;

use Modules\Donate\Domain\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Arr;

class GatewayManager
{
    protected array $gateways = [];

    public function __construct(array $gateways)
    {
        foreach ($gateways as $gateway) {
            $this->register($gateway);
        }
    }

    public function register(PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$gateway->id()] = $gateway;
    }

    public function all(): array
    {
        return $this->gateways;
    }

    public function get(string $id): ?PaymentGatewayInterface
    {
        return Arr::get($this->gateways, $id);
    }
}

