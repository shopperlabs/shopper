<?php

declare(strict_types=1);

return [

    'menu' => 'Clientes',
    'single' => 'cliente',
    'title' => 'Gestionar pedidos y detalles de clientes',
    'description' => 'Explora perfiles, sigue la actividad a lo largo del tiempo y gestiona cada cuenta desde un solo lugar.',
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

    'stats' => [
        'total' => 'Total clientes',
        'total_subtitle' => 'Todas las cuentas registradas',
        'new' => 'Nuevos clientes',
        'new_30_days' => 'en los últimos 30 días',
        'new_empty' => 'Ningún cliente nuevo en 30 días',
        'active' => 'Clientes activos',
        'active_subtitle' => 'realizaron al menos un pedido pagado',
        'active_empty' => 'Aún no hay clientes activos',
        'avg_ltv' => 'Valor promedio de por vida',
        'avg_ltv_subtitle' => 'Ingreso medio por cliente activo',
        'avg_ltv_empty' => 'A la espera del primer pedido pagado',
    ],

    'header' => [
        'since' => 'Cliente desde el :date',
        'orders_count' => '{0} sin pedidos|{1} :count pedido|[2,*] :count pedidos',
        'id' => 'ID cliente #:id',
        'active' => 'Activo',
        'inactive' => 'Inactivo',
        'previous' => 'Cliente anterior',
        'next' => 'Cliente siguiente',
        'last_order' => 'Último pedido :time',
    ],

    'details' => [
        'title' => 'Detalles del cliente',
        'id' => 'ID del cliente',
        'copy_id' => 'Copiar ID del cliente',
        'copied' => 'Copiado al portapapeles',
        'created' => 'Creado',
        'email_status' => 'Email',
        'email_verified' => 'Verificado',
        'email_unverified' => 'No verificado',
        'marketing_on' => 'Suscrito',
        'marketing_off' => 'No suscrito',
        'two_factor_on' => 'Activado',
        'two_factor_off' => 'Desactivado',
    ],

    'contact' => [
        'title' => 'Información de contacto',
        'no_phone' => 'Sin número de teléfono registrado',
    ],

    'default_address' => [
        'title' => 'Dirección por defecto',
        'empty' => 'Este cliente no tiene ninguna dirección registrada.',
    ],

    'create' => [
        'description' => 'Crea una cuenta de cliente, define sus credenciales y envíale opcionalmente un correo de bienvenida con sus datos de acceso.',
    ],

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
        'marketing' => 'Correos de marketing',
        'two_factor' => 'Autenticación 2FA',
    ],

    'addresses' => [
        'title' => 'Direcciones',
        'shipping' => 'Dirección de envío',
        'billing' => 'Dirección de facturación',
        'shipping_section' => 'Direcciones de envío',
        'billing_section' => 'Direcciones de facturación',
        'default' => 'Por defecto',
        'customer' => 'Direcciones del cliente',
        'empty_text' => 'Este cliente aún no tiene una dirección de entrega o facturación.',
        'shipping_empty_title' => 'Sin dirección de envío',
        'shipping_empty' => 'Este cliente aún no ha registrado ninguna dirección de envío.',
        'billing_empty_title' => 'Sin dirección de facturación',
        'billing_empty' => 'Este cliente aún no ha registrado ninguna dirección de facturación.',
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
