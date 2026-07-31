<?php

declare(strict_types=1);

return [

    'cart' => [
        'empty' => 'Le panier est vide.',
        'nothing_to_collect' => 'Le panier n\'a aucun montant à encaisser.',
        'no_zone' => 'Le panier n\'a pas de zone de livraison, aucune option de livraison ne s\'y applique.',
        'email_required' => 'Renseignez une adresse e-mail avant de finaliser le panier.',
        'metadata_too_large' => 'Les métadonnées sont trop volumineuses.',
    ],

    'purchasable' => [
        'not_available' => 'Ce produit n\'est pas disponible à la vente.',
        'sold_through_variants' => 'Ce produit se vend via ses déclinaisons, ajoutez l\'une d\'entre elles.',
        'missing_price' => 'Ce produit n\'a pas de prix pour la devise :currency.',
    ],

    'shipping' => [
        'method_required' => 'Choisissez un mode de livraison avant de finaliser le panier.',
        'option_not_available' => 'Cette option de livraison n\'est pas disponible pour le panier.',
        'option_gone' => 'L\'option de livraison sélectionnée n\'est plus disponible.',
        'price_changed' => 'Le prix de la livraison a changé depuis sa sélection. Choisissez à nouveau le mode de livraison pour confirmer le nouveau prix.',
        'origin_missing' => 'Les tarifs transporteurs en direct sont indisponibles : aucun emplacement de stock ne peut servir d\'origine d\'expédition.',
        'carrier_unavailable' => 'Les tarifs de « :carrier » sont temporairement indisponibles.',
        'currency_mismatch' => 'Les options en :currency ont été retirées : le panier est en :cart_currency.',
    ],

    'payment' => [
        'method_required' => 'Choisissez un moyen de paiement avant de finaliser le panier.',
        'method_required_for_session' => 'Choisissez un moyen de paiement avant d\'ouvrir une session de paiement.',
        'method_not_available' => 'Ce moyen de paiement n\'est pas disponible pour le panier.',
        'method_not_configured' => 'Le moyen de paiement « :method » n\'est pas configuré.',
        'session_mismatch' => 'La session de paiement ne correspond plus au total du panier. Créez une nouvelle session de paiement et réessayez.',
    ],

    'order' => [
        'restricted_includes' => 'Seul `items` peut être inclus sur la consultation de confirmation de commande. Utilisez l\'endpoint de commande du compte pour la commande complète.',
    ],

    'catalog' => [
        'unknown_currency' => 'La devise ":code" est inconnue ou non activée sur cette boutique.',
        'no_currency' => 'Le tri et le filtre par prix nécessitent une devise configurée sur la boutique.',
    ],

    'promotion' => [
        'not_found' => 'Ce code promotionnel est invalide.',
        'not_applicable' => 'Ce code promotionnel ne s\'applique pas à votre panier.',
    ],

];
