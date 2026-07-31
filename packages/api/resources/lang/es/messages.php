<?php

declare(strict_types=1);

return [

    'cart' => [
        'empty' => 'El carrito está vacío.',
        'nothing_to_collect' => 'El carrito no tiene ningún importe que cobrar.',
        'no_zone' => 'El carrito no tiene zona de envío, ninguna opción de envío se le aplica.',
        'email_required' => 'Indica una dirección de correo electrónico antes de finalizar el carrito.',
        'metadata_too_large' => 'Los metadatos son demasiado grandes.',
    ],

    'purchasable' => [
        'not_available' => 'Este producto no está disponible para la venta.',
        'sold_through_variants' => 'Este producto se vende a través de sus variantes, añade una de ellas.',
        'missing_price' => 'Este producto no tiene precio para la moneda :currency.',
    ],

    'shipping' => [
        'method_required' => 'Selecciona un método de envío antes de completar el carrito.',
        'option_not_available' => 'Esta opción de envío no está disponible para el carrito.',
        'option_gone' => 'La opción de envío seleccionada ya no está disponible.',
        'price_changed' => 'El precio del envío cambió desde su selección. Vuelve a seleccionar el método de envío para confirmar el nuevo precio.',
        'origin_missing' => 'Las tarifas en vivo de los transportistas no están disponibles: ningún almacén puede actuar como origen del envío.',
        'carrier_unavailable' => 'Las tarifas de «:carrier» no están disponibles temporalmente.',
        'currency_mismatch' => 'Las opciones en :currency fueron retiradas: el carrito está en :cart_currency.',
    ],

    'payment' => [
        'method_required' => 'Selecciona un método de pago antes de completar el carrito.',
        'method_required_for_session' => 'Selecciona un método de pago antes de abrir una sesión de pago.',
        'method_not_available' => 'Este método de pago no está disponible para el carrito.',
        'method_not_configured' => 'El método de pago «:method» no está configurado.',
        'session_mismatch' => 'La sesión de pago ya no coincide con el total del carrito. Crea una nueva sesión de pago e inténtalo de nuevo.',
    ],

    'order' => [
        'restricted_includes' => 'Solo `items` puede incluirse en la consulta de confirmación del pedido. Usa el endpoint de pedidos de la cuenta para el pedido completo.',
    ],

    'catalog' => [
        'unknown_currency' => 'La moneda ":code" es desconocida o no está habilitada en esta tienda.',
        'no_currency' => 'Ordenar y filtrar por precio requiere una moneda configurada en la tienda.',
    ],

    'promotion' => [
        'not_found' => 'Este código promocional no es válido.',
        'not_applicable' => 'Este código promocional no se aplica a tu carrito.',
    ],

];
