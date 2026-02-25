<?php

declare(strict_types=1);

return [

    'title' => 'Impuestos',
    'single' => 'Zona fiscal',
    'description' => 'Gestiona las zonas fiscales, tasas y el comportamiento de los impuestos de tu tienda.',
    'add_action' => 'Añadir zona fiscal',
    'empty_heading' => 'Sin zonas fiscales',
    'empty_detail_heading' => 'Ninguna zona fiscal seleccionada',
    'empty_detail_description' => 'Selecciona una zona fiscal para ver sus detalles y tasas.',
    'inclusive' => 'Imp. incl.',
    'exclusive' => 'Imp. excl.',
    'inclusive_help' => 'Activar para precios con impuestos incluidos (ej. Europa, África).',
    'tax_behavior' => 'Comportamiento fiscal',
    'provider' => 'Proveedor de impuestos',
    'system_default' => 'Sistema (por defecto)',
    'province_code' => 'Código de provincia / estado',
    'province_code_help' => 'Código de subdivisión ISO 3166-2 (ej. US-CA, FR-IDF, GB-ENG).',
    'name_help' => 'Nombre de visualización opcional para esta zona (ej. California, Île-de-France).',

    'rates' => [
        'title' => 'Tasas de impuestos',
        'add' => 'Añadir tasa',
        'add_heading' => 'Tasa de impuesto para :name',
        'update' => 'Actualizar :name',
        'rate' => 'Tasa',
        'empty_heading' => 'Sin tasas configuradas',
        'default_help' => 'Usar esta tasa cuando no se aplique ninguna excepción específica de producto.',
        'combinable' => 'Acumulable',
        'combinable_help' => 'Permitir que esta tasa se acumule con las tasas de la zona padre.',
    ],

    'overrides' => [
        'add' => 'Crear excepción',
        'add_heading' => 'Excepción de tasa para :name',
        'update' => 'Actualizar excepción :name',
        'description' => 'Una excepción aplica una tasa de impuesto diferente a productos, tipos de productos o categorías específicas.',
        'targets' => 'Objetivos',
        'targets_help' => 'Selecciona los productos, tipos de productos o categorías a los que se aplica esta excepción.',
        'target_type' => 'Tipo de objetivo',
        'target_value' => 'Valor del objetivo',
        'add_target' => 'Añadir objetivo',
        'product_types' => 'Tipos de productos',
        'products' => 'Productos',
        'categories' => 'Categorías',
    ],

];
