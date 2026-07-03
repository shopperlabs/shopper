<?php

declare(strict_types=1);

return [

    'apply' => [
        'entire_order' => 'Pedido completo',
        'specific_products' => 'Productos específicos',
    ],

    'condition' => [
        'apply_to' => 'Aplicar a',
        'eligibility' => 'Elegibilidad',
    ],

    'eligibility' => [
        'everyone' => 'Todos',
        'everyone_description' => 'Cualquiera puede usar esta promoción.',
        'specific_customers' => 'Clientes específicos',
        'customers_description' => 'Solo los clientes seleccionados pueden usarla.',
    ],

    'requirement' => [
        'none' => 'Ninguno',
        'min_amount' => 'Monto mínimo de compra',
        'min_quantity' => 'Cantidad mínima de artículos',
    ],

    'type' => [
        'percentage' => 'Porcentaje',
        'percentage_description' => 'Descuento aplicado en %',
        'fixed_amount' => 'Monto fijo',
        'fixed_amount_description' => 'Descuento en números enteros',
    ],

];
