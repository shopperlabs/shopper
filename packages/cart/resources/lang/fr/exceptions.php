<?php

declare(strict_types=1);

return [

    'cart_completed' => 'Le panier a déjà été finalisé.',
    'cart_not_found' => 'Panier introuvable.',
    'cart_owned_by_another_customer' => 'Ce panier appartient à un autre client.',
    'payment_session_collected' => 'Ce panier a déjà été payé, finalisez la commande.',
    'insufficient_stock' => 'Stock insuffisant pour cet article.',
    'price_changed' => 'Le prix d\'un article de votre panier a changé. Veuillez vérifier votre panier avant de commander.',
    'missing_price' => 'Cet article n\'a pas de prix en :currency.',
    'quantity_minimum' => 'La quantité doit être d\'au moins 1.',
    'line_locked' => 'Cet article a un prix négocié : sa quantité ne peut pas être modifiée.',
    'line_metadata_conflict' => 'Cet article est déjà dans le panier avec d\'autres métadonnées : modifiez cette ligne ou retirez-la d\'abord.',
    'price_negative' => 'Un prix ne peut pas être négatif.',
    'quantity_rule' => [
        'minimum' => 'Cet article se commande par :minimum au minimum.',
        'maximum' => 'Cet article ne peut pas être commandé au-delà de :maximum.',
        'increment' => 'Cet article se commande par multiples de :increment.',
    ],
    'discount_not_found' => 'Code de réduction introuvable.',

    'discount_limit' => [
        'global' => 'La réduction « :code » a atteint sa limite d\'utilisation et ne peut plus être appliquée.',
        'per_user' => 'Vous avez déjà utilisé la réduction « :code », limitée à une utilisation par client.',
        'automatic' => 'promotion automatique',
    ],

];
