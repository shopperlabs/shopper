<?php

declare(strict_types=1);

return [

    'menu' => 'Promociones',
    'single' => 'descuento',
    'title' => 'Gestionar descuentos y promociones',
    'description' => 'Crea y gestiona códigos de descuento y promociones que se apliquen al finalizar la compra o en los pedidos de los clientes.',

    'empty_message' => 'No se encontró ningún descuento...',
    'search' => 'Buscar código de descuento',
    'name_helptext' => 'Los clientes ingresarán este código de descuento al finalizar la compra.',

    'method' => 'Método',
    'method_code' => 'Código de descuento',
    'method_code_description' => 'Los clientes ingresan un código al finalizar la compra para usar este descuento.',
    'method_automatic' => 'Automático',
    'method_automatic_description' => 'Se aplica automáticamente cuando el carrito coincide, sin código.',

    'type_percentage_description' => 'Un porcentaje de descuento sobre el pedido o los productos seleccionados.',
    'type_fixed_description' => 'Un importe fijo de descuento sobre el pedido o los productos seleccionados.',
    'apply_to_order_description' => 'Descuenta el total del pedido completo.',
    'apply_to_products_description' => 'Descuenta solo los productos seleccionados.',
    'eligibility_everyone_description' => 'Cualquiera puede usar esta promoción.',
    'eligibility_customers_description' => 'Solo los clientes seleccionados pueden usarla.',

    'exclusivity_class' => 'Clase de exclusividad',
    'exclusivity_class_helptext' => 'Los descuentos de la misma clase nunca se acumulan entre sí.',
    'exclusivity_order' => 'Pedido',
    'exclusivity_product' => 'Producto',
    'exclusivity_shipping' => 'Envío',
    'combinable' => 'Combinable con otros descuentos',
    'combinable_helptext' => 'Permitir que este descuento se acumule con descuentos de otras clases de exclusividad.',
    'priority' => 'Prioridad',
    'priority_helptext' => 'Los números más bajos se evalúan primero cuando varios descuentos compiten.',

    'campaign' => 'Campaña',
    'campaign_description' => 'Asocia este descuento a una campaña para compartir su presupuesto y límites de uso.',
    'campaign_helptext' => 'Los descuentos de una campaña comparten el mismo presupuesto y límites de canje.',
    'campaign_locked_helper' => 'La campaña no puede modificarse una vez que la promoción ha sido utilizada, para mantener la coherencia del seguimiento del presupuesto de la campaña.',
    'campaign_none' => 'Sin campaña',

    'wizard' => [
        'type' => 'Tipo',
        'details' => 'Detalles',
        'campaign' => 'Campaña',
    ],

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

    'create' => [
        'description' => 'Configura un nuevo código de descuento. El resumen a la derecha se actualiza mientras llenas el formulario para ver exactamente lo que tus clientes recibirán.',
    ],

    'edit' => [
        'description' => 'Actualiza este descuento y revisa su uso real y su impacto en los ingresos.',
    ],

    'sections' => [
        'general' => 'General',
        'general_description' => 'Código, tipo y visibilidad del descuento.',
        'configuration' => 'Configuración',
        'configuration_description' => 'Cuántas veces se puede usar el descuento y cuándo está activo.',
        'targeting' => 'Segmentación',
        'targeting_description' => 'Qué productos y clientes son elegibles para este descuento.',
        'combinations' => 'Combinaciones',
        'combinations_description' => 'Controla cómo se acumula este descuento con otros y su prioridad de evaluación.',
        'advanced' => 'Avanzado',
        'advanced_description' => 'Metadatos personalizados adjuntos al descuento.',
    ],

    'zone_frozen_helper' => 'La zona no puede modificarse una vez que un descuento de monto fijo ya ha sido utilizado. Se preserva la coherencia de la moneda en los pedidos existentes.',

    'summary' => [
        'title' => 'Resumen de la regla',
        'empty' => 'Selecciona un tipo y un valor para ver el resumen actualizarse en tiempo real.',
        'uses_total' => 'usos máximo',
        'type_percentage' => ':value % de descuento',
        'type_fixed_amount' => ':amount de descuento',
        'minimum_price' => 'Carrito ≥ :amount',
        'minimum_quantity' => 'Mín :count artículo|Mín :count artículos',
        'visibility_public' => 'Público',
        'visibility_hidden' => 'Oculto',
        'rows' => [
            'type' => 'Tipo',
            'code' => 'Código',
            'zone' => 'Zona',
            'applies' => 'Aplica a',
            'for' => 'Para',
            'minimum' => 'Mínimo',
            'usage' => 'Usos',
            'usage_value' => '{1} :count uso máx|[2,*] :count usos máx',
            'active' => 'Activo',
            'visibility' => 'Visibilidad',
        ],
    ],

    'stats' => [
        'title' => 'Rendimiento',
        'usage' => 'Usos',
        'orders' => 'Pedidos',
        'gross_revenue' => 'Ingreso bruto',
        'discount_cost' => 'Costo del descuento',
        'aov_with' => 'AOV con el código',
        'disclaimer' => 'Las estadísticas incluyen los pedidos pagados desde la migración del seguimiento de descuentos.',
    ],

    'actions' => [
        'duplicate' => 'Duplicar',
        'duplicate_confirm_heading' => '¿Duplicar este descuento?',
        'duplicate_confirm_description' => 'Se creará una copia con un nuevo código sufijado `_COPY`, con el toggle activo apagado y un contador de uso reiniciado. Serás redirigido al nuevo descuento para terminar de editarlo.',
        'duplicate_in_progress' => 'Una duplicación ya está en curso.',
        'duplicate_success' => 'Descuento duplicado como :code.',
    ],

    'products_picker' => [
        'title' => 'Selecciona los productos a los que se aplica el descuento',
        'description' => 'Elige uno o varios productos. Aparecerán en el formulario tras confirmar la selección.',
        'button' => 'Examinar productos',
        'bulk_add' => 'Añadir productos seleccionados',
        'empty' => 'No se encontró ningún producto.',
        'empty_field' => 'No hay producto seleccionado. Haz clic en "Examinar productos" para añadir uno.',
        'required' => 'Selecciona al menos un producto cuando el descuento se aplica a productos específicos.',
    ],

    'customers_picker' => [
        'title' => 'Selecciona los clientes elegibles para el descuento',
        'description' => 'Elige uno o varios clientes. Aparecerán en el formulario tras confirmar la selección.',
        'button' => 'Examinar clientes',
        'bulk_add' => 'Añadir clientes seleccionados',
        'empty' => 'No se encontró ningún cliente.',
        'empty_field' => 'No hay cliente seleccionado. Haz clic en "Examinar clientes" para añadir uno.',
        'required' => 'Selecciona al menos un cliente cuando el descuento se dirige a clientes específicos.',
    ],

    'apply_to_switch' => [
        'heading' => '¿Cambiar a todo el pedido?',
        'description' => 'Has elegido productos específicos para este descuento. Cambiar a todo el pedido los eliminará de la selección.',
        'submit' => 'Sí, cambiar y vaciar',
        'cancel' => 'Mantener los productos',
    ],

    'eligibility_switch' => [
        'heading' => '¿Cambiar a todos?',
        'description' => 'Has elegido clientes específicos. Cambiar a todos los eliminará de la selección.',
        'submit' => 'Sí, cambiar y vaciar',
        'cancel' => 'Mantener los clientes',
    ],

];
