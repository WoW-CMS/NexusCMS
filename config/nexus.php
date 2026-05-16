<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nexus CMS options
    |--------------------------------------------------------------------------
    |
    | Runtime feature flags consumed by app services. Values are loaded from
    | config so they can be cached safely in production.
    |
    */
    'redis_enabled' => env('REDIS_ENABLED', false),
];
