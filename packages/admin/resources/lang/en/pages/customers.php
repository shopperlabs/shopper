<?php

declare(strict_types=1);

return [

    'menu' => 'Customers',
    'single' => 'customer',
    'title' => 'Manage customer orders & details',
    'description' => 'Browse profiles, track lifetime activity, and manage every account from one place.',
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

    'stats' => [
        'total' => 'Total customers',
        'total_subtitle' => 'All registered accounts',
        'new' => 'New customers',
        'new_30_days' => 'in the last 30 days',
        'new_empty' => 'No new customers in 30 days',
        'active' => 'Active customers',
        'active_subtitle' => 'placed at least one paid order',
        'active_empty' => 'No active customers yet',
        'avg_ltv' => 'Average lifetime value',
        'avg_ltv_subtitle' => 'Average revenue per active customer',
        'avg_ltv_empty' => 'Awaiting first paid order',
    ],

    'header' => [
        'since' => 'Customer since :date',
        'orders_count' => '{0} no orders|{1} :count order|[2,*] :count orders',
        'id' => 'Customer ID #:id',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'previous' => 'Previous customer',
        'next' => 'Next customer',
        'last_order' => 'Last order :time',
    ],

    'details' => [
        'title' => 'Customer details',
        'id' => 'Customer ID',
        'copy_id' => 'Copy customer ID',
        'copied' => 'Copied to clipboard',
        'created' => 'Created',
        'email_status' => 'Email',
        'email_verified' => 'Verified',
        'email_unverified' => 'Unverified',
        'marketing_on' => 'Subscribed',
        'marketing_off' => 'Unsubscribed',
        'two_factor_on' => 'Enabled',
        'two_factor_off' => 'Disabled',
    ],

    'contact' => [
        'title' => 'Contact information',
        'no_phone' => 'No phone number on file',
    ],

    'default_address' => [
        'title' => 'Default address',
        'empty' => 'This customer has no address on file.',
    ],

    'create' => [
        'description' => 'Create a customer account, set credentials, and optionally send a welcome email with their login details.',
    ],

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
        'marketing' => 'Marketing emails',
        'two_factor' => 'Two-factor auth',
    ],

    'addresses' => [
        'title' => 'Addresses',
        'shipping' => 'Shipping Address',
        'billing' => 'Billing Address',
        'shipping_section' => 'Shipping addresses',
        'billing_section' => 'Billing addresses',
        'default' => 'Default',
        'customer' => 'Customer addresses',
        'empty_text' => 'This customer does not yet have a delivery or billing address.',
        'shipping_empty_title' => 'No shipping address',
        'shipping_empty' => 'This customer has not registered any shipping address yet.',
        'billing_empty_title' => 'No billing address',
        'billing_empty' => 'This customer has not registered any billing address yet.',
    ],

    'orders' => [
        'placed' => 'Order Placed',
        'total' => 'Total',
        'ship_to' => 'Ship To',
        'order_number' => 'Order :number',
        'details' => 'Order Details',
        'items' => 'Order items',
        'view' => 'View order',
        'empty_text' => 'No orders found...',
        'no_shipping' => 'No shipping method',
        'estimated' => 'Shipping date',
    ],

    'anonymize' => [
        'action' => 'Anonymize customer',
        'title' => 'Anonymize this customer',
        'description' => 'This action will permanently anonymize all personal data for this customer (name, email, phone, addresses). Order history will be preserved for accounting purposes. This action cannot be undone.',
        'confirm' => 'Yes, anonymize',
        'success' => 'Customer has been successfully anonymized.',
        'first_name' => 'Deleted',
        'last_name' => 'Customer',
    ],

    'picker' => [
        'title' => 'Select customers',
        'description' => 'Search and select one or more customers.',
        'bulk_add' => 'Add selected customers',
        'empty' => 'No matching customer found.',
    ],
];
