<?php

declare(strict_types=1);

return [

    'menu' => 'Pedidos',
    'single' => 'pedido',
    'title' => 'Gestionar pedidos de clientes',
    'show_title' => 'Detalle del pedido ~ :number',
    'content' => 'Cuando los clientes realizan pedidos, aquí es donde se realizará todo el procesamiento, la gestión de reembolsos y el seguimiento de su pedido.',
    'total_price_description' => 'Este precio no incluye impuestos aplicables sobre el producto o sobre el cliente.',

    'no_shipping_method' => 'Este pedido no tiene un método de envío',
    'read_about_shipping' => 'Leer más sobre envíos',
    'no_payment_method' => 'Este pedido no tiene un método de pago',
    'read_about_payment' => 'Leer más sobre métodos de pago',
    'payment_actions' => 'Acciones de pago',
    'send_invoice' => 'Enviar factura',
    'private_notes' => 'Notas privadas',
    'customer_date' => 'Cliente desde :date',
    'customer_orders' => 'ya ha realizado :number pedido(s)',
    'customer_infos' => 'Información de contacto',
    'customer_infos_empty' => 'No hay información disponible para este cliente',
    'no_customer' => 'Cliente desconocido',

    'modals' => [
        'archived_number' => 'Pedido archivado :number',
        'archived_notice' => '¿Estás seguro de que deseas archivar este pedido? Esta acción cambiará los ingresos que has obtenido hasta ahora en tu tienda.',
    ],

    'notifications' => [
        'archived' => '¡El pedido se ha archivado exitosamente!',
        'cancelled' => '¡El pedido se ha cancelado exitosamente!',
        'note_added' => 'Tu nota se ha añadido a este pedido.',
        'registered' => '¡El pedido se ha registrado exitosamente!',
        'paid' => '¡El pedido está marcado como pagado!',
        'completed' => '¡El pedido está marcado como completado!',
    ],

];
