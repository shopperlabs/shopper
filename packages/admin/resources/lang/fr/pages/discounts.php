<?php

declare(strict_types=1);

return [

    'menu' => 'Réductions',
    'single' => 'réduction',
    'title' => 'Gérer les remises et les promotions',
    'description' => 'Créez et gérez les codes de réduction et de promotion qui s\'appliquent à la caisse ou aux commandes des clients.',

    'empty_message' => 'Aucune réduction trouvée...',
    'search' => 'Rechercher un code de réduction',
    'name_helptext' => 'Les clients saisiront ce code de réduction lors du paiement.',
    'percentage' => 'Pourcentage',
    'percentage_description' => 'Remise appliquée en %',
    'fixed_amount' => 'Montant fixe',
    'fixed_amount_description' => 'Réduction en nombres entiers',
    'configuration_description' => "Le code de réduction s'applique à partir du moment où vous appuyez sur le bouton de publication et reste actif s'il n'est pas modifié.",
    'condition_description' => "Le code de réduction s'applique à tous les produits s'il n'est pas modifié.",
    'applies_to' => 'S\'applique à',
    'entire_order' => 'Toute la commande',
    'specific_products' => 'Produits spécifiques',
    'select_products' => 'Sélectionnez des produits',
    'min_requirement' => 'Exigences minimales',
    'none' => 'Aucun',
    'min_amount' => 'Montant d\'achat minimum (:currency)',
    'min_value' => 'Valeur minimale requise',
    'applies_only_selected' => 'S\'applique uniquement aux produits sélectionnés.',
    'min_quantity' => 'Quantité minimale d\'éléments',
    'customer_eligibility' => 'Admissibilité du client',
    'everyone' => 'Tout le monde',
    'specific_customers' => 'Clients spécifiques',
    'select_customers' => 'Sélectionnez des clients',
    'usage_limits' => 'Limites d\'utilisation',
    'usage_label' => 'Limiter le nombre de fois que ce coupon peut être utilisée au total',
    'usage_label_description' => "Cette limite s'applique à tous les clients, pas individuellement.",
    'usage_value' => 'Valeur limite d\'utilisation',
    'limit_one_per_user' => 'Limité à une utilisation par client',
    'active_dates' => 'Dates actives',
    'active_dates_description' => 'Les dates durant lesquelles la remise sera utilisable pour les utilisateurs.',
    'start_date' => 'Date de début',
    'choose_start_date' => 'Choisissez la date de début',
    'end_date' => 'Date de fin',
    'choose_end_date' => 'Choisissez la date de fin',
    'empty_code' => 'Aucune information saisie pour le moment.',
    'count_items' => ':count éléments',
    'min_purchase' => 'Achat minimum de',

    'modals' => [
        'stock_available' => ':stock disponible',
        'add_products' => 'Ajouter des produits',
        'add_selected_products' => 'Ajouter les produits sélectionnés',
        'search_product' => 'Rechercher un produit par nom',

        'add_customers' => 'Ajouter des clients',
        'search_customer' => 'Rechercher un client par nom',
        'add_selected_customers' => 'Ajouter des clients sélectionnés',

        'remove' => [
            'title' => 'Supprimer ce code',
            'description' => 'Vous êtes sûr de vouloir supprimer ce code ? Toutes ces données seront supprimées. Cette action ne peut pas être annulée.',
            'success_message' => 'Suppression du code de réduction avec succès !',
        ],
    ],

    'active_today' => 'Actif aujourd\'hui',
    'active_from_today' => 'Actif à partir d\'aujourd\'hui',
    'active_from' => 'Actif à partir de :date',
    'active_date' => 'Actif :date',
    'active_from_to' => 'Actif de :start à :end',
    'one_per_customer' => 'un par client',

    'save' => 'Code de réduction :code enregistré avec succès!',
    'total_use' => 'Utilisations',

];
