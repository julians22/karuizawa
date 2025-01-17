<?php

return [
    'auth' => [
        'app_token' => env('ACCURATE_APP_TOKEN', null),
        'app_key' => env('ACCURATE_APP_KEY', null),
        'app_secret' => env('ACCURATE_APP_SECRET', null),
        'app_url' => env('ACCURATE_APP_URL', null),
        'signature' => env('ACCURATE_SIGNATURE', null),
        // 'db' => 290005 //
        'db' => 1638959,

        'scopes' => [
            'warehouse_view',
            'customer_save',
            'customer_view',
            'customer_delete',
            'item_save',
            'item_view',
            'item_adjustment_save',
            'sales_order_save',
            'glaccount_view',
            'glaccount_save',
            'sales_invoice_save',
            'sales_invoice_view',
            'sales_receipt_save',
            'sales_receipt_view',
            'purchase_invoice_view',
            'receive_item_view',
            'stock_mutation_history_view',
            'department_view',
            'department_save'
        ]
    ]
]

?>
