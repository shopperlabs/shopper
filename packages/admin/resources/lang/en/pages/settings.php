<?php

declare(strict_types=1);

return [

    'empty_country_selector' => 'Please select a country',
    'logo_description' => 'The logo of your store that will be visible on your site. This assets will appear on your invoices.',

    'settings' => [
        'title' => 'Store Setting',
        'store_details' => 'Store details',
        'store_detail_summary' => 'Your customers will use this information to contact you.',
        'email_helper' => 'Your customers will use this address if they need to contact you.',
        'phone_number_helper' => 'Your customers will use this phone number if they need to call you directly.',
        'assets' => 'Assets',
        'assets_summary' => 'The logo and cover image of your store that will be visible on your site. This assets will appear on your invoices.',
        'store_address' => 'Store address',
        'store_address_summary' => 'This address will appear on your invoices. You can edit the address used.',
        'store_currency' => 'Store currency',
        'store_currency_summary' => 'This is the currency your products are sold in. After your first sale, currency is locked in and can’t be changed.',
        'social_links' => 'Social links',
        'social_links_summary' => 'Information about your different accounts on social networks. Users will be able to contact you directly on your official pages.',
        'update_information' => 'Update information',
    ],

    'payment' => [
        'title' => 'Payments Methods',
        'stripe_description' => 'Accept payments on your store using third-party providers such as Stripe.',
        'stripe_enabled' => 'Stripe is available for your store.',
        'stripe_disabled' => 'Stripe is not enabled.',
        'stripe_provider' => 'This provider allows you to integrate Stripe PHP into your store to allow your customers to make payments.',
        'stripe_about' => 'Learn more about Stripe Payment',
        'stripe_actions' => 'Enabled Stripe Payment',
        'stripe_environment' => 'Stripe has two environments Sandbox and Live, make sure to use sandbox for testing before going live.',
        'stripe_dashboard' => 'API Keys can be grabbed from',
        'create_payment' => 'Create payment method',
        'no_method' => 'No payment methods found',
    ],

    'validations' => [
        'country' => 'Country is required',
        'shop_name' => 'Store name is required',
        'shop_email' => 'Store e-mail is required',
    ],

    'notifications' => [
        'inventory' => 'Inventory Successfully saved',
    ],

    'roles_permissions' => [
        'title' => 'User Roles & Access Management',
        'header_title' => 'Administrators & roles',
        'role_available' => 'Administrator role available',
        'role_available_summary' => 'A role provides access to predefined menus and features so that depending on the assigned role and permissions an administrator can have access to what he needs.',
        'new_role' => 'Add new role',
        'admin_accounts' => 'Administrators accounts',
        'admin_accounts_summary' => 'These are the members who are already in your store with their associated roles. You can assign new roles to existing member here.',
        'add_admin' => 'Add administrator',
        'users_role' => 'Users & roles',
        'login_information' => 'Login information',
        'login_information_summary' => 'This information will be useful for the administrator to connect to the administration of Shopper.',
        'send_invite' => 'Send Invite',
        'send_invite_summary' => 'Send an invitation to this administrator by email with his login information.',
        'personal_information' => 'Personal Information',
        'personal_information_summary' => 'Information related to the admin profile.',
        'role_information' => 'Role Information',
        'role_information_summary' => 'Assign roles to this administrator who will limit the actions he can do.',
        'roles' => 'Roles',
        'permissions' => 'Permissions',
        'choose_role' => 'Choose a role for this admin',
        'create_permission' => 'Create permission',
        'role_alert_msg' => 'You are about to update the admin role, this could block your access to the dashboard.',
        'with_role_name' => 'with :name role',
        'permissions_in_role' => 'in :name role',
        'custom_permission' => 'Custom permission',
        'delete_team_member' => 'Are you sure you want to delete this member?',
    ],

    'location' => [
        'description' => 'Manage the places you stock inventory, fulfill orders, and sell products.',
        'count' => 'You’re using :count of 4 locations available.',
        'add' => 'Add Inventory',
        'detail' => 'Details',
        'detail_summary' => 'Give this location a short name to make it easy to identify. You’ll see this name in areas like products.',
        'address' => 'Inventory address',
        'address_summary' => "Your inventory's complete information. Please put valide informations this can be accessible for your customers.",
        'set_default' => 'Set as default inventory',
        'set_default_summary' => 'Inventory at this location is available for sale online and will use as default',
        'is_default' => 'This is your default inventory. To change whether you fulfill online orders from this inventory, select another default inventory first.',
    ],

    'analytics' => [
        'google' => 'Google Analytics',
        'google_description' => 'Google Analytics allows you to track visitors to your site and generates reports that will help you with your marketing.',
        'gtag' => 'Google Tag Manager',
        'gtag_description' => 'Google Tag Manager allows marketing managers to easily add tags (Analytics, remarketing, etc.)',
        'pixel' => 'Facebook Pixel',
        'pixel_description' => 'Facebook Pixel helps you create ad campaigns to find new customers who are most like your buyers.',
        'no_json' => 'No json file added',
    ],

    'legal' => [
        'title' => 'Legal policy',
        'refund' => 'Refund policy',
        'privacy' => 'Privacy policy',
        'shipping' => 'Shipping policy',
        'terms_of_use' => 'Terms of use',
        'summary' => 'Define the :policy to which all users and consumers of the products in your store will be subject.',
    ],
];
