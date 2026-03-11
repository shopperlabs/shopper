<?php

declare(strict_types=1);

return [

    'system' => [
        'dashboard' => [
            'display_name' => 'Access Dashboard',
            'description' => 'Allows the user to access the admin dashboard.',
        ],
        'settings' => [
            'display_name' => 'Access Settings',
            'description' => 'Allows the user to view and manage the settings pages.',
        ],
        'users' => [
            'display_name' => 'View Users',
            'description' => 'Allows the user to access the team and roles management area.',
        ],
    ],

    'generate' => [
        'browse' => [
            'display_name' => 'Browse :item',
            'description' => 'Allows browsing all :item records with search, filters and pagination.',
        ],
        'read' => [
            'display_name' => 'Read :item',
            'description' => 'Allows reading the full details of a single :item record.',
        ],
        'edit' => [
            'display_name' => 'Edit :item',
            'description' => 'Allows editing and updating an existing :item record.',
        ],
        'create' => [
            'display_name' => 'Create :item',
            'description' => 'Allows creating a new :item record.',
        ],
        'delete' => [
            'display_name' => 'Delete :item',
            'description' => 'Allows permanently deleting a :item record.',
        ],
    ],

];
