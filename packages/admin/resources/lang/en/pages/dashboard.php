<?php

declare(strict_types=1);

return [

    'menu' => 'Dashboard',
    'welcome_message' => 'Welcome to Shopper',
    'welcome_description' => 'Here\'s what you need to get your store up and running.',

    'cards' => [
        'doc_title' => 'Documentation',
    ],

    'guide' => [
        'title' => 'Setup guide',
        'description' => 'Complete these steps to start selling.',
        'progress' => 'of :total completed',
        'dismiss' => 'Dismiss',
        'footer_hint' => 'You can always access these settings later.',

        'steps' => [
            'add_product' => [
                'title' => 'Add your first product',
                'description' => 'Add products with prices, images, and variants to start building your catalog.',
                'action' => 'Add a product',
            ],
            'create_collection' => [
                'title' => 'Create a collection',
                'description' => 'Organize your products into collections to make it easy for customers to browse your store.',
                'action' => 'Create a collection',
            ],
            'setup_zones' => [
                'title' => 'Set up shipping zones',
                'description' => 'Configure your shipping zones to define where you deliver and at what cost.',
                'action' => 'Set up shipping',
            ],
            'setup_payments' => [
                'title' => 'Set up payment methods',
                'description' => 'Add payment methods so your customers can pay for their orders.',
                'action' => 'Set up payments',
            ],
            'setup_taxes' => [
                'title' => 'Configure taxes',
                'description' => 'Set up tax zones and rates to automatically calculate taxes on orders.',
                'action' => 'Configure taxes',
            ],
        ],
    ],

    'stats' => [
        'revenue' => 'Total Revenue',
        'products' => 'Total Products',
        'orders' => 'Total Orders',
        'customers' => 'Total Customers',
        'vs_last_month' => 'vs last month',
        'view_more' => 'View more',
    ],

    'chart' => [
        'heading' => 'Performance',
        'series_label' => 'Revenue',
    ],

    'recent_orders' => [
        'heading' => 'Recent Orders',
        'view_all' => 'View all',
        'empty' => 'No orders yet.',
    ],

    'top_products' => [
        'heading' => 'Top Selling Products',
        'view_all' => 'View all',
        'product' => 'Product',
        'sales' => 'Sales',
        'reviews' => 'Reviews',
        'empty' => 'No sales yet.',
    ],

    'addons' => [
        'title' => 'Extend your store',
        'badge' => 'Add-on',
        'learn_more' => 'Learn more',
        'configure' => 'Configure carriers',

        'stripe' => [
            'title' => 'Stripe',
            'description' => 'Accept credit cards, Apple Pay, and Google Pay with Stripe.',
        ],
        'carriers' => [
            'title' => 'Shipping carriers',
            'description' => 'Connect UPS, FedEx, USPS and more for live shipping rates.',
        ],
    ],

];
