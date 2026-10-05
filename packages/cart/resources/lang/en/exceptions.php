<?php

declare(strict_types=1);

return [

    'cart_completed' => 'Cart has already been completed.',
    'cart_not_found' => 'Cart not found.',
    'cart_owned_by_another_customer' => 'This cart belongs to another customer.',
    'payment_session_collected' => 'This cart has already been paid. Complete the checkout to place the order.',
    'insufficient_stock' => 'Insufficient stock for this item.',
    'price_changed' => 'The price of an item in your cart has changed. Please review your cart before checking out.',
    'missing_price' => 'This item has no price in :currency.',
    'quantity_minimum' => 'Quantity must be at least 1.',
    'line_locked' => 'This item has a negotiated price and its quantity cannot be changed.',
    'line_metadata_conflict' => 'This item is already in the cart with different metadata: update that line or remove it first.',
    'price_negative' => 'A price cannot be negative.',
    'quantity_rule' => [
        'minimum' => 'This item must be ordered in a quantity of at least :minimum.',
        'maximum' => 'This item cannot be ordered in a quantity above :maximum.',
        'increment' => 'This item must be ordered in multiples of :increment.',
    ],
    'discount_not_found' => 'Discount code not found.',

    'discount_limit' => [
        'global' => 'The discount ":code" has reached its usage limit and can no longer be applied.',
        'per_user' => 'You have already used the discount ":code", which is limited to one use per customer.',
        'automatic' => 'automatic promotion',
    ],

];
