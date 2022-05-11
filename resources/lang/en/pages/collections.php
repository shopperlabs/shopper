<?php

return [

    'title' => 'Organize your products into various collections',
    'content' => 'Create and manage all your collections to help your customers easily find groups or types of products.',
    'automatic' => 'Automatic',
    'automatic_description' => 'Products that match the conditions you set will automatically be added to collection.',
    'manual' => 'Manual',
    'manual_description' => 'Add the products to this collection one by one.',
    'filter_type' => 'Collection Type',
    'product_conditions' => 'Product Conditions',

    'conditions' => [
        'title' => 'Conditions',
        'products_match' => 'Products must match:',
        'all' => 'All conditions',
        'any' => 'Any condition',
        'rules' => 'Rules',
        'choose_rule' => 'Choose a rule',
        'select_operator' => 'Select Operator',
        'add' => 'Add condition',
        'add_another' => 'Add another condition',
        'update' => 'Update conditions',
    ],

    'availability_description' => 'Specify a publication date so that your collections are scheduled on your store.',

    'order' => [
        'alpha_asc' => 'Alpha Asc',
        'alpha_desc' => 'Alpha Desc',
        'price_asc' => 'Price Asc',
        'price_desc' => 'Price Desc',
        'created_asc' => 'Created Asc',
        'created_desc' => 'Created Desc',
    ],

    'rules' => [
        'product_title' => 'Product title',
        'product_brand' => 'Product brand',
        'product_category' => 'Product category',
        'product_price' => 'Product price',
        'compare_at_price' => 'Compare at price',
        'inventory_stock' => 'Inventory stock',
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

    'empty_collections' => 'There are no products in this collection. Add or change conditions to dynamically add products.',

    'modal' => [
        'title' => 'Add Products to collection',
        'search' => 'Search product',
        'search_placeholder' => 'Search product by name',
        'action' => 'Add Selected Products',
        'stock' => ':stock available',
        'success_message' => 'Selected product(s) added',
    ],
];
