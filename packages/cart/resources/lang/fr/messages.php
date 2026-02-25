<?php

declare(strict_types=1);

return [

    'exceptions' => [
        'cart_completed' => 'Le panier a déjà été finalisé.',
        'cart_not_found' => 'Panier introuvable.',
        'insufficient_stock' => 'Stock insuffisant pour cet article.',
    ],

    'discount' => [
        'not_found' => 'Code de réduction introuvable.',
        'not_active' => 'La réduction n\'est pas active.',
        'not_started' => 'La réduction n\'a pas encore commencé.',
        'expired' => 'La réduction a expiré.',
        'usage_limit_reached' => 'La limite d\'utilisation de la réduction est atteinte.',
        'already_used' => 'La réduction a déjà été utilisée par ce client.',
        'requires_login' => 'La réduction nécessite un client connecté.',
        'customer_not_eligible' => 'Le client n\'est pas éligible à cette réduction.',
        'not_available_in_zone' => 'La réduction n\'est pas disponible dans cette zone.',
        'min_amount_not_reached' => 'Le montant minimum d\'achat n\'est pas atteint.',
        'min_quantity_not_reached' => 'La quantité minimum n\'est pas atteinte.',
    ],

];
