<?php

declare(strict_types=1);

return [

    'cart_completed' => 'El carrito ya ha sido completado.',
    'cart_not_found' => 'Carrito no encontrado.',
    'cart_owned_by_another_customer' => 'Este carrito pertenece a otro cliente.',
    'payment_session_collected' => 'Este carrito ya se ha pagado. Finaliza la compra para realizar el pedido.',
    'insufficient_stock' => 'Stock insuficiente para este artículo.',
    'price_changed' => 'El precio de un artículo de tu carrito ha cambiado. Revisa tu carrito antes de finalizar la compra.',
    'missing_price' => 'Este artículo no tiene precio en :currency.',
    'quantity_minimum' => 'La cantidad debe ser al menos 1.',
    'line_locked' => 'Este artículo tiene un precio negociado y su cantidad no se puede modificar.',
    'line_metadata_conflict' => 'Este artículo ya está en el carrito con otros metadatos: modifique esa línea o elimínela primero.',
    'price_negative' => 'Un precio no puede ser negativo.',
    'quantity_rule' => [
        'minimum' => 'Este artículo debe pedirse en una cantidad de al menos :minimum.',
        'maximum' => 'Este artículo no puede pedirse en una cantidad superior a :maximum.',
        'increment' => 'Este artículo debe pedirse en múltiplos de :increment.',
    ],
    'discount_not_found' => 'Código de descuento no encontrado.',

    'discount_limit' => [
        'global' => 'El descuento «:code» alcanzó su límite de uso y ya no puede aplicarse.',
        'per_user' => 'Ya has usado el descuento «:code», limitado a un uso por cliente.',
        'automatic' => 'promoción automática',
    ],

];
