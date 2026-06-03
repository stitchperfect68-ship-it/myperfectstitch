<?php

return [
    'api_key'        => env('LENCO_API_KEY', ''),
    'webhook_secret' => env('LENCO_WEBHOOK_SECRET', ''),
    'base_url'       => env('LENCO_BASE_URL', 'https://api.lenco.co'),
    'country'        => env('LENCO_COUNTRY', 'zm'),
    'currency'       => 'ZMW',
];
