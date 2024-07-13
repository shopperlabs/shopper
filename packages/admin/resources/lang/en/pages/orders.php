<?php

declare(strict_types=1);

return [

    'menu' => 'Orders',
    'single' => 'order',
    'title' => 'Manage customers orders',
    'show_title' => 'Detail Order ~ :number',
    'content' => 'When customers place orders, this is where all the processing will be done, the management of refunds and the tracking of their order.',
    'total_price_description' => 'This price does not include applicable taxes on the product or on the customer.',

    'no_shipping_method' => "This order don't have a shipping method",
    'read_about_shipping' => 'Read more about shipping',
    'no_payment_method' => "This order don't have a payment method",
    'read_about_payment' => 'Read more about payment method',
    'payment_actions' => 'Payment actions',
    'send_invoice' => 'Send invoice',
    'private_notes' => 'Private notes',
    'customer_date' => 'Customer since :date',
    'customer_orders' => 'has already placed :number order(s)',
    'customer_infos' => 'Contact Information',
    'customer_infos_empty' => 'No information available for this customer',
    'no_customer' => 'Client inconnu',

    'modals' => [
        'archived_number' => 'Archived order :number',
        'archived_notice' => 'Are you sure you want to archived this order? This action will change the income you have earned so far in your store.',
    ],

    'notifications' => [
        'archived' => 'The orders has successfully archived !',
        'cancelled' => 'The order has successfully cancelled !',
        'note_added' => 'Your note has been added to this order.',
        'registered' => 'The order has successfully registered !',
        'paid' => 'The order is marked as paid !',
        'completed' => 'The order is marked as completed !',
    ],

];
