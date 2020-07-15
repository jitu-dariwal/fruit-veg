<?php

return [
    'shipping_token' => env('SHIPPING_API_TOKEN'),
    'name' => env('SHOP_NAME', 'Fruitandveg'),
    'url' => env('APP_URL').'/fruit/',
    'country' => 'UNITED KINGDOM',
    'country_id' => env('SHOP_COUNTRY_ID', 225),
    'weight' => env('SHOP_WEIGHT', 'lbs'),
   // 'email' => env('SHOP_EMAIL', 'info@fruitandveg.co.uk'),
    'email' => env('SHOP_EMAIL', 'mukesh.s@dotsquares.com'),
    'phone' => env('SHOP_PHONE', '0808 141 2828'),
    'warehouse' => [
        'name' => 'Fruit And Veg.co.uk',
        'address_1' => 'Unit 5D Bates Industrial Estate',
        'address_2' => 'The Old Brickworks,Church Road,',
        'state' => 'Essex',
        'city' => 'Harold Wood',
        'country' => 'US',
        'zip' => 'RM30HU',
    ]
];