<?php

declare(strict_types=1);

return [

    'system' => [
        'dashboard' => [
            'display_name' => 'Tillgång till instrumentpanel',
            'description' => 'Frågar efter tillgång till admin-instrumentpanelen.',
        ],
        'settings' => [
            'display_name' => 'Tillgång till inställningar',
            'description' => 'Tillåter användaren att visa och hantera inställningssidor.',
        ],
        'users' => [
            'display_name' => 'Visa användare',
            'description' => 'Tillåter användaren att komma åt området för hantering av team och roller.',
        ],
    ],

    'generate' => [
        'browse' => [
            'display_name' => 'Bläddra i :item',
            'description' => 'Tillåter bläddring av alla :item-poster med sökning, filter och paginering.',
        ],
        'read' => [
            'display_name' => 'Läs :item',
            'description' => 'Tillåter att se fullständiga detaljer för en enskild :item-post.',
        ],
        'edit' => [
            'display_name' => 'Redigera :item',
            'description' => 'Tillåter redigering och uppdatering av en befintlig :item-post.',
        ],
        'create' => [
            'display_name' => 'Skapa :item',
            'description' => 'Tillåter att skapa en ny :item-post.',
        ],
        'delete' => [
            'display_name' => 'Radera :item',
            'description' => 'Tillåter att permanent radera en :item-post.',
        ],
    ],

];
