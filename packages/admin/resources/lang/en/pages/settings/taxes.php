<?php

declare(strict_types=1);

return [

    'title' => 'Taxes',
    'single' => 'Tax Zone',
    'description' => 'Manage tax zones, rates, and tax behavior for your store.',
    'add_action' => 'Add tax zone',
    'empty_heading' => 'No tax zones',
    'empty_detail_heading' => 'No tax zone selected',
    'empty_detail_description' => 'Select a tax zone to view its details and rates.',
    'inclusive' => 'Incl. tax',
    'exclusive' => 'Excl. tax',
    'inclusive_help' => 'Enable for VAT-style inclusive pricing (e.g. Europe, Africa).',
    'tax_behavior' => 'Tax behavior',
    'provider' => 'Tax provider',
    'system_default' => 'System (default)',
    'province_code' => 'Province / State code',
    'province_code_help' => 'ISO 3166-2 subdivision code (e.g. US-CA, FR-IDF, GB-ENG).',
    'name_help' => 'Optional display name for this zone (e.g. California, Île-de-France).',

    'rates' => [
        'title' => 'Tax Rates',
        'add' => 'Add Rate',
        'add_heading' => 'Tax rate for :name',
        'update' => 'Update :name',
        'rate' => 'Rate',
        'empty_heading' => 'No rates configured',
        'default_help' => 'Use this rate when no product-specific override applies.',
        'combinable' => 'Combinable',
        'combinable_help' => 'Allow this rate to stack with parent zone rates.',
    ],

    'overrides' => [
        'add' => 'Create Override',
        'add_heading' => 'Override rate for :name',
        'update' => 'Update override :name',
        'description' => 'An override applies a different tax rate to specific products, product types, or categories.',
        'targets' => 'Targets',
        'targets_help' => 'Select which products, product types, or categories this override applies to.',
        'target_type' => 'Target type',
        'target_value' => 'Target value',
        'add_target' => 'Add target',
        'product_types' => 'Product types',
        'products' => 'Products',
        'categories' => 'Categories',
    ],

];
