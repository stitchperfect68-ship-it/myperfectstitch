<?php

return [
    // Secret API token (server-side only — never expose to the browser)
    'api_key'        => env('LENCO_API_KEY', ''),

    // Public key from the Lenco Pay dashboard, used by the inline checkout widget
    'public_key'     => env('LENCO_PUBLIC_KEY', ''),

    // Optional. If omitted the webhook hash key is derived as SHA256(api_key)
    'webhook_secret' => env('LENCO_WEBHOOK_SECRET', ''),

    'base_url'       => env('LENCO_BASE_URL', 'https://api.lenco.co'),
    'inline_url'     => env('LENCO_INLINE_URL', 'https://pay.lenco.co/js/v1/inline.js'),
    'country'        => env('LENCO_COUNTRY', 'zm'),
    'currency'       => env('LENCO_CURRENCY', 'ZMW'),

    // Who bears the transaction fee: "merchant" or "customer"
    'bearer'         => env('LENCO_BEARER', 'customer'),
];
