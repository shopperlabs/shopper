<?php

return [

    'title' => 'Manage customer orders & details',
    'content' => 'This is where you can manage your customer information and view their purchase history.',

    'overview' => 'Profile overview',
    'overview_description' => 'Use a permanent address where customer can receive mail.',
    'security_title' => 'Security',
    'security_description' => 'Enter a random password that this user will use to login to his account.',
    'address_title' => 'Address',
    'address_description' => 'The primary address of this customer. This address will defined as default shipping address.',
    'notification_title' => 'Notifications',
    'notification_description' => 'Inform your customers about their account.',
    'marketing_email' => 'Customer agreed to receive marketing emails.',
    'marketing_description' => 'You should ask your customers for permission before you subscribe them to your marketing emails if you got one.',
    'send_credentials' => 'Send customer credentials.',
    'credential_description' => 'An email will be sent to this customer with these connection credentials.',

    'period' => 'Customer for :period',

    'modal' => [
        'title' => 'Archived this customer',
        'description' => 'Are you sure you want to deactivate this customer? All of his data (orders & addresses) will be permanently removed from your store forever. This action cannot be undone.',
        'success_message' => 'You have successfully archived this customer, it\'s no longer available in your customer list.',
    ],

    'profile' => [
        'title' => 'Profile',
        'description' => 'All your customer\'s public information can be found here.',
        'account' => 'Account',
        'account_description' => 'Manage how information is used on the customer account.',
        'marketing' => 'Email Marketing',
        'two_factor' => 'Two-Factor Authentication',
    ],

    'addresses' => [
        'title' => 'Addresses',
        'shipping' => 'Shipping Address',
        'billing' => 'Billing Address',
        'default' => 'Default address',
        'customer' => 'Customer addresses',
        'empty_text' => 'This customer does not yet have a delivery or billing address.',
    ],

    'orders' => [
        'placed' => 'Order Placed',
        'total' => 'Total',
        'ship_to' => 'Ship To',
        'order_number' => 'Order :number',
        'details' => 'Order Details',
        'items' => 'Order items',
        'payment_method' => 'Payment method',
        'shipping_method' => 'Shipping method',
        'no_shipping' => 'No shipping method',
        'estimated' => 'Estimated Delivery:',
        'view' => 'View order',
        'empty_text' => 'No orders found...',
    ],

];
