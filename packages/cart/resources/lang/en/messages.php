<?php

declare(strict_types=1);

return [

    'exceptions' => [
        'cart_completed' => 'Cart has already been completed.',
        'cart_not_found' => 'Cart not found.',
        'insufficient_stock' => 'Insufficient stock for this item.',
    ],

    'discount' => [
        'not_found' => 'Discount code not found.',
        'not_active' => 'Discount is not active.',
        'not_started' => 'Discount has not started yet.',
        'expired' => 'Discount has expired.',
        'usage_limit_reached' => 'Discount usage limit reached.',
        'already_used' => 'Discount already used by this customer.',
        'requires_login' => 'Discount requires a logged-in customer.',
        'customer_not_eligible' => 'Customer is not eligible for this discount.',
        'not_available_in_zone' => 'Discount is not available in this zone.',
        'min_amount_not_reached' => 'Minimum purchase amount not reached.',
        'min_quantity_not_reached' => 'Minimum quantity not reached.',
    ],

];
