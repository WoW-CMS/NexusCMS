<?php

namespace Modules\Donate\Infrastructure\Gateways;

use Modules\Donate\Domain\Interfaces\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Braintree\Gateway;

class BraintreeGateway implements PaymentGatewayInterface
{
    protected Gateway $gateway;

    public function __construct()
    {
        $cfg = config('donate.gateways.braintree', []);
        $this->gateway = new Gateway([
            'environment' => $cfg['environment'] ?? 'sandbox',
            'merchantId'  => $cfg['merchant_id'] ?? '',
            'publicKey'   => $cfg['public_key'] ?? '',
            'privateKey'  => $cfg['private_key'] ?? '',
        ]);
    }

    public function id(): string
    {
        return 'braintree';
    }

    public function displayName(): string
    {
        return 'Braintree';
    }

    public function createCheckout(int $amount, array $meta = []): array
    {
        if (isset($meta['nonce'])) {
            $result = $this->gateway->transaction()->sale([
                'amount' => number_format($amount, 2, '.', ''),
                'paymentMethodNonce' => $meta['nonce'],
                'options' => ['submitForSettlement' => true],
            ]);
            if ($result->success) {
                return [
                    'status' => 'success',
                    'transaction_id' => $result->transaction->id,
                    'amount' => $amount,
                ];
            }
            $errors = [];
            foreach ($result->errors->deepAll() as $error) {
                $errors[] = "{$error->code}: {$error->message}";
            }
            return [
                'status' => 'error',
                'errors' => $errors,
            ];
        }

        $token = $this->gateway->clientToken()->generate();
        return [
            'client_token' => $token,
            'amount' => $amount,
        ];
    }

    public function handleCallback(Request $request): array
    {
        return ['status' => 'success', 'gateway' => $this->id()];
    }

    public function handleWebhook(Request $request): array
    {
        return ['received' => true];
    }
}
