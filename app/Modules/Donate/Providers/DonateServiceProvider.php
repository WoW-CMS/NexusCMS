<?php

namespace Modules\Donate\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Donate\Services\GatewayManager;
use Modules\Donate\Infrastructure\Gateways\StripeGateway;
use Modules\Donate\Infrastructure\Gateways\BraintreeGateway;
use Modules\Donate\Infrastructure\Gateways\MercadoPagoGateway;

class DonateServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Donate';

    public function register(): void
    {
        $this->mergeConfigFrom(base_path('config/donate.php'), 'donate');

        $this->app->singleton(GatewayManager::class, function () {
            $enabled = config('donate.enabled_gateways', []);
            $map = [
                'braintree' => new BraintreeGateway(),
            ];
            $gateways = [];
            foreach ($enabled as $id) {
                if (isset($map[$id])) {
                    $gateways[] = $map[$id];
                }
            }
            return new GatewayManager($gateways);
        });
    }
}
