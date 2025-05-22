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
            'sales_invoice_save',
            'sales_invoice_view',
            'customer_view',
            'customer_save',
            'customer_delete',
            'sales_receipt_save',
            'sales_receipt_view',
            'glaccount_view',
            'glaccount_save',
            'sales_invoice_view',
            'purchase_invoice_view',
            'receive_item_view',
            'stock_mutation_history_view',
            'department_view',
            'department_save',
            'item_adjustment_save',
            'sales_order_view',
        ],

    ],
    'semi_custom_sku' => 'MTM-02CA',
    'customer_list' => [
        'AST' => 'C.00359',
        'PIK' => 'C.00651',
    ],
    'warehouse_list' => [
        'AST' => 'Ashta Mall',
        'PIK' => 'Gudang PIK',
    ],
    'trans_no' => [
        'PIK' => '1800',
        'AST' => '1801',
        'BANK' => '96'
    ],
    'bank_no' => '1101020119',
    'bank_account_type' => 'CASH_BANK'
]

?>
