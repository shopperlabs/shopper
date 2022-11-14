<?php

return [

    'title' => 'Manage discounts and promotions',
    'description' => 'Create & Manage discount and promotions codes that apply at checkout or customers orders.',

    'actions' => [
        'create' => 'Create code',
        'update' => 'Update code :code',
    ],

    'empty_message' => 'No discount found...',
    'search' => 'Search discount code',
    'name_helptext' => 'Customers will enter this discount code at checkout.',
    'percentage' => 'Percentage',
    'fixed_amount' => 'Fixed amount',
    'applies_to' => 'Applies To',
    'entire_order' => 'Entire order',
    'specific_products' => 'Specific products',
    'select_products' => 'Select products',
    'min_requirement' => 'Minimum requirements',
    'none' => 'None',
    'min_amount' => 'Minimum purchase amount (:currency)',
    'min_value' => 'Min Required Value',
    'applies_only_selected' => 'Applies only to selected products.',
    'min_quantity' => 'Minimum quantity of items',
    'customer_eligibility' => 'Customer eligibility',
    'everyone' => 'Everyone',
    'specific_customers' => 'Specific customers',
    'select_customers' => 'Select customers',
    'usage_limits' => 'Usage limits',
    'usage_label' => 'Limit number of times this discount can be used in total',
    'usage_value' => 'Usage limit value',
    'limit_one_per_user' => 'Limit to one use per customer',
    'active_dates' => 'Active dates',
    'start_date' => 'Start date',
    'choose_start_date' => 'Choose start date period',
    'end_date' => 'End date',
    'choose_end_date' => 'Choose end date',
    'empty_code' => 'No information entered yet.',
    'count_items' => ':count items',
    'min_purchase' => 'Minimum purchase of',

    'modals' => [
        'stock_available' => ':stock available',
        'add_products' => 'Add Products',
        'add_selected_products' => 'Add Selected Products',
        'search_product' => 'Search product by name',

        'add_customers' => 'Add Customers',
        'search_customer' => 'Search customer by name',
        'add_selected_customers' => 'Add Selected Customers',

        'remove' => [
            'title' => 'Delete this code',
            'description' => 'Are you sure you want to delete this code? All this data will be removed. This action cannot be undone.',
            'success_message' => 'Remove discount code successfully!',
        ],
    ],

    'active_today' => 'Active today',
    'active_from_today' => 'Active from today',
    'active_from' => 'Active from :date',
    'active_date' => 'Active :date',
    'active_from_to' => 'Active from :start to :end',
    'one_per_customer' => 'one per customer',

    'add_message' => 'Discount code :code created successfully!',
    'update_message' => 'Discount code :code updated successfully!',

];
