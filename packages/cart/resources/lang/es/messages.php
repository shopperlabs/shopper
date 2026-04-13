<?php

declare(strict_types=1);

return [

    'exceptions' => [
        'cart_completed' => 'El carrito ya ha sido completado.',
        'cart_not_found' => 'Carrito no encontrado.',
        'insufficient_stock' => 'Stock insuficiente para este artículo.',
    ],

    'discount' => [
        'not_found' => 'Código de descuento no encontrado.',
        'not_active' => 'El descuento no está activo.',
        'not_started' => 'El descuento aún no ha comenzado.',
        'expired' => 'El descuento ha expirado.',
        'usage_limit_reached' => 'Se ha alcanzado el límite de uso del descuento.',
        'already_used' => 'El descuento ya ha sido utilizado por este cliente.',
        'requires_login' => 'El descuento requiere un cliente con sesión iniciada.',
        'customer_not_eligible' => 'El cliente no es elegible para este descuento.',
        'not_available_in_zone' => 'El descuento no está disponible en esta zona.',
        'min_amount_not_reached' => 'No se ha alcanzado el monto mínimo de compra.',
        'min_quantity_not_reached' => 'No se ha alcanzado la cantidad mínima.',
    ],

];
