<?php

declare(strict_types=1);

return [

    'menu' => 'Descuentos',
    'single' => 'descuento',
    'title' => 'Gestionar descuentos y promociones',
    'description' => 'Crea y gestiona códigos de descuento y promociones que se apliquen al finalizar la compra o en los pedidos de los clientes.',

    'empty_message' => 'No se encontró ningún descuento...',
    'search' => 'Buscar código de descuento',
    'name_helptext' => 'Los clientes ingresarán este código de descuento al finalizar la compra.',
    'percentage' => 'Porcentaje',
    'percentage_description' => 'Descuento aplicado en %',
    'fixed_amount' => 'Monto fijo',
    'fixed_amount_description' => 'Descuento en números enteros',
    'configuration_description' => 'El código de descuento se aplica desde el momento en que presionas el botón publicar, y permanece activo si no se modifica.',
    'condition_description' => 'El código de descuento se aplica a todos los productos si no se modifica.',
    'applies_to' => 'Aplica a',
    'entire_order' => 'Pedido completo',
    'specific_products' => 'Productos específicos',
    'select_products' => 'Seleccionar productos',
    'min_requirement' => 'Requisitos mínimos',
    'none' => 'Ninguno',
    'min_amount' => 'Monto mínimo de compra (:currency)',
    'min_value' => 'Valor mínimo requerido',
    'applies_only_selected' => 'Aplica solo a productos seleccionados.',
    'min_quantity' => 'Cantidad mínima de artículos',
    'customer_eligibility' => 'Elegibilidad del cliente',
    'everyone' => 'Todos',
    'specific_customers' => 'Clientes específicos',
    'select_customers' => 'Seleccionar clientes',
    'usage_limits' => 'Límites de uso',
    'usage_label' => 'Limitar el número de veces que este descuento se puede usar en total',
    'usage_label_description' => 'Este límite aplica a todos los clientes, no individualmente.',
    'usage_value' => 'Valor del límite de uso',
    'limit_one_per_user' => 'Limitar a un uso por cliente',
    'active_dates' => 'Fechas activas',
    'active_dates_description' => 'Las fechas en las que el descuento estará disponible para los usuarios.',
    'start_date' => 'Fecha de inicio',
    'choose_start_date' => 'Elegir periodo de fecha de inicio',
    'end_date' => 'Fecha de finalización',
    'choose_end_date' => 'Elegir fecha de finalización',
    'empty_code' => 'No hay información ingresada aún.',
    'count_items' => ':count artículos',
    'min_purchase' => 'Compra mínima de',

    'modals' => [
        'stock_available' => ':stock disponible',
        'add_products' => 'Añadir productos',
        'add_selected_products' => 'Añadir productos seleccionados',
        'search_product' => 'Buscar producto por nombre',

        'add_customers' => 'Añadir clientes',
        'search_customer' => 'Buscar cliente por nombre',
        'add_selected_customers' => 'Añadir clientes seleccionados',

        'remove' => [
            'title' => 'Eliminar este código',
            'description' => '¿Estás seguro de que deseas eliminar este código? Todos estos datos serán eliminados. Esta acción no se puede deshacer.',
            'success_message' => '¡Código de descuento eliminado exitosamente!',
        ],
    ],

    'active_today' => 'Activo hoy',
    'active_from_today' => 'Activo desde hoy',
    'active_from' => 'Activo desde :date',
    'active_date' => 'Activo :date',
    'active_from_to' => 'Activo desde :start hasta :end',
    'one_per_customer' => 'uno por cliente',

    'save' => '¡Código de descuento :code guardado exitosamente!',
    'total_use' => 'Canjes',

];
