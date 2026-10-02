<?php

return [
    'order_status' => [
        'pending' => 'pending',
        'processing' => 'processing',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ],
    'payment_status' => [
        'pending' => 'pending',
        'processing' => 'processing',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ],
    'payment_method' => [
        'manual-tf' => 'TF',
        'credit-card' => 'KREDIT',
        'debit-card' => 'DEBIT',
        'qris' => 'QR',
    ],
    'order_source' => [
        'offline' => 'offline',
        'online' => 'online',
    ],
    'shipment_status' => [
        'pending' => 'pending',
        'confirmed' => 'confirmed',
        'allocated' => 'allocated',
        'picking_up' => 'picking_up',
        'picked' => 'picked',
        'dropping_off' => 'dropping_off',
        'delivered' => 'delivered',
        'returned' => 'returned',
        'cancelled' => 'cancelled',
        'courier_not_found' => 'courier_not_found',
    ],
    'semi_custom_name' => 'MTM-02CA',
    'semi_custom_outer_name' => 'MTM-OS-CA',
    'semi_custom_light_jacket_name' => 'MTM-LJ-CA',
];
