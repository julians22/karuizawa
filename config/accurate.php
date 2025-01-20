<?php

return [
    'auth' => [
        'app_token' => env('ACCURATE_APP_TOKEN', null),
        'app_key' => env('ACCURATE_APP_KEY', null),
        'app_secret' => env('ACCURATE_APP_SECRET', null),
        'app_url' => env('ACCURATE_APP_URL', null),
        'signature' => env('ACCURATE_SIGNATURE', null),
        'redirect_uri' => env('ACCURATE_REDIRECT_URI', null),
        // 'db' => 290005 //
        'db' => env('ACCURATE_DB', null),

        'scopes' => [
            'warehouse_view',
            'item_save',
            'item_view',
            'purchase_invoice_save',
            'auto_number_view',
            'sales_order_save',
        ],

    ],
    'semi_custom_sku' => 'MTM-02CA',
    'customer_list' => [
        'AST' => 'C.00359',
        'PIK' => 'C.00651',
    ]
]

?>
