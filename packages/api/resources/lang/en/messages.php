<?php

declare(strict_types=1);

return [

    'cart' => [
        'empty' => 'The cart is empty.',
        'nothing_to_collect' => 'The cart has no amount to collect.',
        'no_zone' => 'The cart has no shipping zone, so no shipping option applies to it.',
        'email_required' => 'Provide an email address before completing the cart.',
    ],

    'purchasable' => [
        'not_available' => 'This product is not available for purchase.',
        'sold_through_variants' => 'This product is sold through its variants, add one of them instead.',
        'missing_price' => 'This product has no price for the :currency currency.',
    ],

    'shipping' => [
        'method_required' => 'Set a shipping method on the cart before completing it.',
        'option_not_available' => 'This shipping option is not available for the cart.',
        'option_gone' => 'The selected shipping option is no longer available.',
        'price_changed' => 'The shipping price changed since it was selected. Set the shipping method again to confirm the new price.',
        'origin_missing' => 'Live carrier rates are unavailable: no inventory location can act as the shipment origin.',
        'carrier_unavailable' => 'Rates from ":carrier" are temporarily unavailable.',
        'currency_mismatch' => 'Options priced in :currency were removed: the cart is priced in :cart_currency.',
    ],

    'payment' => [
        'method_required' => 'Set a payment method on the cart before completing it.',
        'method_required_for_session' => 'Set a payment method on the cart before opening a payment session.',
        'method_not_available' => 'This payment method is not available for the cart.',
        'method_not_configured' => 'The ":method" payment method is not configured.',
        'session_mismatch' => 'The payment session no longer matches the cart total. Create a new payment session and try again.',
    ],

    'order' => [
        'restricted_includes' => 'Only `items` can be included on the order confirmation lookup. Use the account order endpoint for the full order.',
    ],

];
