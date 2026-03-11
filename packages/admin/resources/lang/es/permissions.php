<?php

declare(strict_types=1);

return [

    'system' => [
        'dashboard' => [
            'display_name' => 'Acceder al panel',
            'description' => 'Permite al usuario acceder al panel de administración.',
        ],
        'settings' => [
            'display_name' => 'Acceder a la configuración',
            'description' => 'Permite al usuario ver y gestionar las páginas de configuración.',
        ],
        'users' => [
            'display_name' => 'Ver usuarios',
            'description' => 'Permite al usuario acceder a la gestión del equipo y los roles.',
        ],
    ],

    'generate' => [
        'browse' => [
            'display_name' => 'Explorar :item',
            'description' => 'Permite explorar todos los registros de :item con búsqueda, filtros y paginación.',
        ],
        'read' => [
            'display_name' => 'Leer :item',
            'description' => 'Permite leer el detalle completo de un registro :item.',
        ],
        'edit' => [
            'display_name' => 'Editar :item',
            'description' => 'Permite editar y actualizar un registro :item existente.',
        ],
        'create' => [
            'display_name' => 'Crear :item',
            'description' => 'Permite crear un nuevo registro :item.',
        ],
        'delete' => [
            'display_name' => 'Eliminar :item',
            'description' => 'Permite eliminar permanentemente un registro :item.',
        ],
    ],

];
