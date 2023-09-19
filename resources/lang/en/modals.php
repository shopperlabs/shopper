<?php

declare(strict_types=1);

return [
    'permissions' => [
        'new' => 'New permission',
        'new_description' => 'Add a new permission and directly assign to this role',
        'labels' => [
            'name' => 'Permission name (in lowercase)',
        ],
    ],

    'roles' => [
        'new' => 'Add new role',
        'new_description' => 'Add a new role and assign permissions for administrators.',
        'labels' => [
            'name' => 'Name (in lowercase)',
        ],
        'confirm_delete_msg' => 'Are you sure you want to remove this role? All users who had this role will no longer be able to access the actions given by this role',
    ],

    'attributes' => [
        'new_value' => 'Add new value for :attribute',
        'key_description' => 'The key will be used for the values in storage for the forms (option, radio, etc.). Must be in slug format',
        'update_value' => 'Update value for :name',
    ],

    'inventories' => [
        'confirm_delete_msg' => 'Are you sure you want to delete this inventory? All this data will be removed. This action cannot be undone',
    ],

    'mailable' => [
        'delete_title' => 'Delete :class Mailable',
        'confirm_delete_msg' => 'Are you sure you want to delete this Mailable class? If this class is used in your project this action will create a bug in your site',
        'delete_template' => 'Delete :template Template',
        'confirm_delete_template' => 'Are you sure you want to delete this Template ?',
    ],

    'payment_method' => [
        'update_title' => 'Update payment method',
    ],
];
