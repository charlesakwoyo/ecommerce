<?php

return [

    /*
    |--------------------------------------------------------------------------
    | VAT
    |--------------------------------------------------------------------------
    |
    | Applied to the order subtotal only when the shipping address country
    | matches the country below (Kenya's standard VAT rate). Orders shipping
    | elsewhere are not taxed.
    |
    */

    'tax' => [
        'rate' => 0.16,
        'country' => 'KE',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping
    |--------------------------------------------------------------------------
    |
    | A flat fee based on whether the shipping address is domestic (Kenya) or
    | international, waived once the order subtotal reaches the free
    | threshold. All amounts are integer cents.
    |
    */

    'shipping' => [
        'domestic_country' => 'KE',
        'domestic_fee' => 30000,
        'international_fee' => 200000,
        'free_threshold' => 1000000,
    ],

];
