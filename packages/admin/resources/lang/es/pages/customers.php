<?php

declare(strict_types=1);

return [

    'menu' => 'Clientes',
    'single' => 'cliente',
    'title' => 'Gestionar pedidos y detalles de clientes',
    'content' => 'Aquí es donde puedes gestionar la información de tus clientes y ver su historial de compras.',

    'overview' => 'Resumen del perfil',
    'overview_description' => 'Usa una dirección permanente donde el cliente pueda recibir correo.',
    'security_title' => 'Seguridad',
    'security_description' => 'Ingresa una contraseña aleatoria que este usuario usará para iniciar sesión en su cuenta.',
    'address_title' => 'Dirección',
    'address_description' => 'La dirección principal de este cliente. Esta dirección se definirá como dirección de envío por defecto.',
    'notification_title' => 'Notificaciones',
    'notification_description' => 'Informa a tus clientes sobre su cuenta.',
    'marketing_email' => 'El cliente aceptó recibir correos de marketing.',
    'marketing_description' => 'Deberías pedir permiso a tus clientes antes de suscribirlos a tus correos de marketing si tienes uno.',
    'send_credentials' => 'Enviar credenciales al cliente.',
    'credential_description' => 'Se enviará un correo a este cliente con estas credenciales de conexión.',

    'period' => 'Cliente por :period',

    'modal' => [
        'title' => 'Archivar este cliente',
        'description' => '¿Estás seguro de que deseas desactivar a este cliente? Todos sus datos (pedidos y direcciones) se eliminarán permanentemente de tu tienda para siempre. Esta acción no se puede deshacer.',
        'success_message' => 'Has archivado exitosamente a este cliente, ya no está disponible en tu lista de clientes.',
    ],

    'profile' => [
        'title' => 'Perfil',
        'description' => 'Toda la información pública de tu cliente se puede encontrar aquí.',
        'account' => 'Cuenta',
        'account_description' => 'Gestiona cómo se utiliza la información en la cuenta del cliente.',
        'marketing' => 'Marketing por correo',
        'two_factor' => 'Autenticación de Dos Factores',
    ],

    'addresses' => [
        'title' => 'Direcciones',
        'shipping' => 'Dirección de envío',
        'billing' => 'Dirección de facturación',
        'default' => 'Dirección por defecto',
        'customer' => 'Direcciones del cliente',
        'empty_text' => 'Este cliente aún no tiene una dirección de entrega o facturación.',
    ],

    'orders' => [
        'placed' => 'Pedido realizado',
        'total' => 'Total',
        'ship_to' => 'Enviar a',
        'order_number' => 'Pedido :number',
        'details' => 'Detalles del pedido',
        'items' => 'Artículos del pedido',
        'view' => 'Ver pedido',
        'empty_text' => 'No se encontraron pedidos...',
        'no_shipping' => 'Sin método de envío',
        'estimated' => 'Fecha de envío',
    ],

    'anonymize' => [
        'action' => 'Anonimizar cliente',
        'title' => 'Anonimizar este cliente',
        'description' => 'Esta acción anonimizará permanentemente todos los datos personales de este cliente (nombre, correo, teléfono, direcciones). El historial de pedidos se conservará con fines contables. Esta acción no se puede deshacer.',
        'confirm' => 'Sí, anonimizar',
        'success' => 'El cliente ha sido anonimizado exitosamente.',
        'first_name' => 'Eliminado',
        'last_name' => 'Cliente',
    ],

];
