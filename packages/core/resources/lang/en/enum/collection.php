<?php

declare(strict_types=1);

return [
    'automatic' => 'Automatic',
    'automatic_description' => 'Products that match the conditions you set will automatically be added to collection.',
    'manual' => 'Manual',
    'manual_description' => 'Add the products to this collection one by one.',

    'rules' => [
        'product_title' => 'Product title',
        'product_brand' => 'Product brand',
        'product_category' => 'Product category',
        'product_price' => 'Product price',
        'compare_at_price' => 'Compare at price',
        'inventory_stock' => 'Inventory stock',
        'product_created_at' => 'Product created date',
        'product_featured' => 'Product featured',
        'product_rating' => 'Product rating',
        'product_sales_count' => 'Product sales count',
    ],

    'operator' => [
        'equals_to' => 'Equals to',
        'not_equals_to' => 'Not equals to',
        'less_than' => 'Less than',
        'greater_than' => 'Greater than',
        'starts_with' => 'Starts with',
        'ends_with' => 'End with',
        'contains' => 'Contains',
        'not_contains' => 'Not contains',
    ],
];
