<?php

declare(strict_types=1);

return [

    'permissions' => [
        'new' => 'Nuevo permiso',
        'new_description' => 'Añadir un nuevo permiso y asignarlo directamente a este rol',
        'labels' => [
            'name' => 'Nombre del permiso (en minúsculas)',
        ],
    ],

    'roles' => [
        'new' => 'Añadir nuevo rol',
        'new_description' => 'Añadir un nuevo rol y asignar permisos para administradores.',
        'labels' => [
            'name' => 'Nombre (en minúsculas)',
        ],
        'confirm_delete_msg' => '¿Estás seguro de que deseas eliminar este rol? Todos los usuarios que tengan este rol ya no podrán acceder a las acciones dadas por este rol',
    ],

    'attributes' => [
        'new_value' => 'Añadir nuevo valor para :attribute',
        'key_description' => 'La clave se utilizará para los valores en el almacenamiento de los formularios (opción, radio, etc.). Debe estar en formato slug',
        'update_value' => 'Actualizar valor para :name',
    ],

    'inventories' => [
        'confirm_delete_msg' => '¿Estás seguro de que deseas eliminar este inventario? Todos estos datos serán eliminados. Esta acción no se puede deshacer',
    ],

];
