<?php

return [
    'merchant_id' => env('BROADPAY_MERCHANT_ID'),
    'api_key'     => env('BROADPAY_API_KEY'),
    'secret_key'  => env('BROADPAY_SECRET_KEY'),
    'base_url'    => env('BROADPAY_BASE_URL', 'https://api.linco.broadpay.zm'),
    'callback_url' => env('APP_URL') . '/payment/callback',
    'webhook_url'  => env('APP_URL') . '/payment/webhook',
    'currency'     => 'ZMW',
];
