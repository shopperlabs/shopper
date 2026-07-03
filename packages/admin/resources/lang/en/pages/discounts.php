<?php

declare(strict_types=1);

return [

    'menu' => 'Promotions',
    'single' => 'discount',
    'title' => 'Manage discounts and promotions',
    'description' => 'Create & Manage discount and promotions codes that apply at checkout or customers orders.',

    'empty_message' => 'No discount found...',
    'search' => 'Search discount code',
    'name_helptext' => 'Customers will enter this discount code at checkout.',

    'method' => 'Method',
    'method_code' => 'Discount code',
    'method_code_description' => 'Customers enter a code at checkout to redeem this discount.',
    'method_automatic' => 'Automatic',
    'method_automatic_description' => 'Applied automatically when the cart matches, without a code.',

    'type_percentage_description' => 'A percentage off the order or selected products.',
    'type_fixed_description' => 'A fixed amount off the order or selected products.',
    'apply_to_order_description' => 'Discounts the entire order total.',
    'apply_to_products_description' => 'Discounts only the selected products.',
    'eligibility_everyone_description' => 'Anyone can use this promotion.',
    'eligibility_customers_description' => 'Only the selected customers can use it.',

    'exclusivity_class' => 'Exclusivity class',
    'exclusivity_class_helptext' => 'Discounts in the same class never stack with each other.',
    'exclusivity_order' => 'Order',
    'exclusivity_product' => 'Product',
    'exclusivity_shipping' => 'Shipping',
    'combinable' => 'Combinable with other discounts',
    'combinable_helptext' => 'Allow this discount to stack with discounts from other exclusivity classes.',
    'priority' => 'Priority',
    'priority_helptext' => 'Lower numbers are evaluated first when several discounts compete.',

    'campaign' => 'Campaign',
    'campaign_description' => 'Attach this discount to a campaign to share its budget and usage caps.',
    'campaign_helptext' => 'Discounts in a campaign share the same budget and redemption limits.',
    'campaign_locked_helper' => 'The campaign cannot be changed once the promotion has been used, to keep campaign budget tracking consistent.',
    'campaign_none' => 'No campaign',

    'wizard' => [
        'type' => 'Type',
        'details' => 'Details',
        'campaign' => 'Campaign',
    ],

    'percentage' => 'Percentage',
    'percentage_description' => 'Discount applied in %',
    'fixed_amount' => 'Fixed amount',
    'fixed_amount_description' => 'Discount in whole numbers',
    'configuration_description' => 'The discount code applies from the moment you press the publish button, and remains active if not modified.',
    'condition_description' => 'The discount code applies to all products if not modified.',
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
    'usage_label_description' => 'This limit applies to all customers, not individually.',
    'usage_value' => 'Usage limit value',
    'limit_one_per_user' => 'Limit to one use per customer',
    'active_dates' => 'Active dates',
    'active_dates_description' => 'The dates on which the discount will be available to users.',
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

    'save' => 'Discount code :code save successfully!',
    'total_use' => 'Redemptions',

    'create' => [
        'description' => 'Set up a new discount code. The summary on the right updates as you fill the form so you can see exactly what your customers will get.',
    ],

    'edit' => [
        'description' => 'Update this discount and review its real usage and revenue impact.',
    ],

    'sections' => [
        'general' => 'General',
        'general_description' => 'Code, type and visibility of the discount.',
        'configuration' => 'Configuration',
        'configuration_description' => 'How many times the discount can be used and when it is active.',
        'targeting' => 'Targeting',
        'targeting_description' => 'Which products and customers are eligible for this discount.',
        'combinations' => 'Combinations',
        'combinations_description' => 'Control how this discount stacks with others and its evaluation priority.',
        'advanced' => 'Advanced',
        'advanced_description' => 'Custom metadata attached to the discount.',
    ],

    'zone_frozen_helper' => 'The zone cannot be changed once a fixed-amount discount has been used. Currency consistency is preserved on existing orders.',

    'summary' => [
        'title' => 'Rule digest',
        'empty' => 'Pick a type and value to see the summary update in real time.',
        'uses_total' => 'uses max',
        'type_percentage' => ':value% off',
        'type_fixed_amount' => ':amount off',
        'minimum_price' => 'Cart ≥ :amount',
        'minimum_quantity' => 'Min :count item|Min :count items',
        'visibility_public' => 'Public',
        'visibility_hidden' => 'Hidden',
        'rows' => [
            'type' => 'Type',
            'code' => 'Code',
            'zone' => 'Zone',
            'applies' => 'Applies',
            'for' => 'For',
            'minimum' => 'Minimum',
            'usage' => 'Usage',
            'usage_value' => '{1} :count use max|[2,*] :count uses max',
            'active' => 'Active',
            'visibility' => 'Visibility',
        ],
    ],

    'stats' => [
        'title' => 'Performance',
        'usage' => 'Usage',
        'orders' => 'Orders',
        'gross_revenue' => 'Gross revenue',
        'discount_cost' => 'Discount cost',
        'aov_with' => 'AOV with code',
        'disclaimer' => 'Stats include paid orders since the discount tracking migration.',
    ],

    'actions' => [
        'duplicate' => 'Duplicate',
        'duplicate_confirm_heading' => 'Duplicate this discount?',
        'duplicate_confirm_description' => 'A copy will be created with a new code suffixed `_COPY`, the active toggle off and a fresh usage counter. You will be redirected to the new discount to finish editing.',
        'duplicate_in_progress' => 'A duplication is already in progress.',
        'duplicate_success' => 'Discount duplicated as :code.',
    ],

    'products_picker' => [
        'title' => 'Pick the products this discount applies to',
        'description' => 'Select one or more products. They will appear in the discount form once you confirm the selection.',
        'button' => 'Browse products',
        'bulk_add' => 'Add selected products',
        'empty' => 'No matching product found.',
        'empty_field' => 'No product selected. Click "Browse products" to add one.',
        'required' => 'Pick at least one product when the discount applies to specific products.',
    ],

    'customers_picker' => [
        'title' => 'Pick the customers eligible for this discount',
        'description' => 'Select one or more customers. They will appear in the discount form once you confirm the selection.',
        'button' => 'Browse customers',
        'bulk_add' => 'Add selected customers',
        'empty' => 'No matching customer found.',
        'empty_field' => 'No customer selected. Click "Browse customers" to add one.',
        'required' => 'Pick at least one customer when the discount targets specific customers.',
    ],

    'apply_to_switch' => [
        'heading' => 'Switch to entire order?',
        'description' => 'You picked specific products for this discount. Switching to the entire order will remove them from the selection.',
        'submit' => 'Yes, switch and clear',
        'cancel' => 'Keep specific products',
    ],

    'eligibility_switch' => [
        'heading' => 'Switch to everyone?',
        'description' => 'You picked specific customers. Switching to everyone will remove them from the selection.',
        'submit' => 'Yes, switch and clear',
        'cancel' => 'Keep specific customers',
    ],

    'eligibility_picker' => [
        'required' => 'Please select at least one target for this eligibility mode.',
    ],
];
