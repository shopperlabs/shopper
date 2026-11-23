<?php

declare(strict_types=1);

return [
    'admin' => [
        'display_name' => 'Administrator',
        'description' => 'Site administrator with access to shop admin panel and developer tools.',
    ],
    'manager' => [
        'display_name' => 'Manager',
        'description' => 'Store manager with access to the catalog, orders, discounts and customers. Settings and team management stay with administrators.',
    ],
    'user' => [
        'display_name' => 'User',
        'description' => 'Site customer role with access on web site.',
    ],
];
