<?php

declare(strict_types=1);

return [

    'menu' => 'Campaigns',
    'single' => 'campaign',
    'title' => 'Manage marketing campaigns',
    'description' => 'Group promotions under a campaign with an optional spend or usage budget.',

    'name' => 'Name',
    'name_placeholder' => 'Summer Sale 2026',
    'currency' => 'Currency',
    'budget_type' => 'Budget type',
    'budget_amount' => 'Budget amount',
    'budget_count' => 'Usage budget',
    'budget' => 'Budget',
    'promotions' => 'Promotions',
    'no_budget' => 'No budget',
    'currency_frozen_helper' => 'The currency cannot be changed once the campaign has recorded budget movements.',

    'create' => [
        'description' => 'Set up a new campaign. The summary on the right updates as you fill the form.',
    ],

    'edit' => [
        'description' => 'Update this campaign and review its budget consumption.',
    ],

    'sections' => [
        'general' => 'General',
        'general_description' => 'Name, currency and visibility of the campaign.',
        'budget' => 'Budget',
        'budget_description' => 'Cap how much this campaign can spend or how many times it can be used.',
        'schedule' => 'Schedule',
        'schedule_description' => 'When the campaign starts and ends.',
        'advanced' => 'Advanced',
        'advanced_description' => 'Custom metadata attached to the campaign.',
    ],

    'summary' => [
        'title' => 'Campaign digest',
        'empty' => 'Fill the form to see the summary update in real time.',
        'rows' => [
            'name' => 'Name',
            'currency' => 'Currency',
            'budget' => 'Budget',
            'schedule' => 'Schedule',
            'visibility' => 'Visibility',
        ],
        'visibility_public' => 'Active',
        'visibility_hidden' => 'Disabled',
    ],

    'budget_panel' => [
        'title' => 'Budget consumption',
        'spend' => 'Spend',
        'count' => 'Usage',
        'none' => 'This campaign has no budget cap.',
    ],

    'promotions_panel' => [
        'title' => 'Attached promotions',
        'empty' => 'No promotion attached to this campaign yet.',
    ],

    'save' => 'Campaign :name saved successfully!',

];
