<?php

return [
    /*
     * The Stripe API key
     */
    'keys' => [
        'public' => env('STRIPE_PUBLIC_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],

    /*
     * Fees configuration
     */
    'fees' => [
        'application_fee' => env('STRIPE_APPLICATION_FEE', 100),
    ],

    /*
     * The charge type to use
     */
    'charge_type' => env('STRIPE_CHARGE_TYPE', 'direct'),
];
