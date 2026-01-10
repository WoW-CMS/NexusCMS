<?php

use Modules\Donate\Domain\Models\DonationPlan;

return [
    /**
     * Enabled gateways for donation transactions.
     * 
     * @var array
     * 
     * Development note: Add more gateways as needed.
     * 
     * @example 'enabled_gateways' => ['braintree', 'paypal'],
     */
    'enabled_gateways' => [
        'braintree',
    ],

    /**
     * Conversion rate between currency and donation points.
     * 
     * @var int
     * 
     * Development note: Update this value as needed.
     * 
     * @example 'dp_rate' => 100,
     */
    'dp_rate' => 100,

    /**
     * Gateways configuration.
     * 
     * @var array
     * 
     * Development note: Add more gateways as needed.
     * 
     * @example 'gateways' => ['braintree' => [...]],
     */
    'gateways' => [
        'braintree' => [
            'environment' => env('BRAINTREE_ENVIRONMENT'),
            'merchant_id' => env('BRAINTREE_MERCHANT_ID'),
            'public_key' => env('BRAINTREE_PUBLIC_KEY'),
            'private_key' => env('BRAINTREE_PRIVATE_KEY'),
        ],
    ],

    /**
     * Donation plans configuration.
     * 
     * @var array|callable
     * 
     * Development note: Set to null to load from database.
     * 
     * @example 'plans' => fn() => DonationPlan::active()->orderBy('sort_order')->get(),
     */
    'plans' => fn() => DonationPlan::active()->orderBy('sort_order')->get(),
];