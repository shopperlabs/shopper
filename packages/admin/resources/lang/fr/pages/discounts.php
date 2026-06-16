<?php

declare(strict_types=1);

return [

    'menu' => 'Promotions',
    'single' => 'réduction',
    'title' => 'Gérer les remises et les promotions',
    'description' => 'Créez et gérez les codes de réduction et de promotion qui s\'appliquent à la caisse ou aux commandes des clients.',

    'empty_message' => 'Aucune réduction trouvée...',
    'search' => 'Rechercher un code de réduction',
    'name_helptext' => 'Les clients saisiront ce code de réduction lors du paiement.',

    'method' => 'Méthode',
    'method_code' => 'Code de réduction',
    'method_code_description' => 'Les clients saisissent un code au paiement pour utiliser cette réduction.',
    'method_automatic' => 'Automatique',
    'method_automatic_description' => 'Appliquée automatiquement lorsque le panier correspond, sans code.',

    'type_percentage_description' => 'Un pourcentage de réduction sur la commande ou les produits sélectionnés.',
    'type_fixed_description' => 'Un montant fixe de réduction sur la commande ou les produits sélectionnés.',
    'apply_to_order_description' => 'Réduit le total de la commande entière.',
    'apply_to_products_description' => 'Réduit uniquement les produits sélectionnés.',
    'eligibility_everyone_description' => 'Tout le monde peut utiliser cette promotion.',
    'eligibility_customers_description' => 'Seuls les clients sélectionnés peuvent l\'utiliser.',

    'exclusivity_class' => 'Classe d\'exclusivité',
    'exclusivity_class_helptext' => 'Les réductions d\'une même classe ne se cumulent jamais entre elles.',
    'exclusivity_order' => 'Commande',
    'exclusivity_product' => 'Produit',
    'exclusivity_shipping' => 'Livraison',
    'combinable' => 'Cumulable avec d\'autres réductions',
    'combinable_helptext' => 'Autoriser cette réduction à se cumuler avec des réductions d\'autres classes d\'exclusivité.',
    'priority' => 'Priorité',
    'priority_helptext' => 'Les nombres les plus petits sont évalués en premier lorsque plusieurs réductions s\'appliquent.',

    'campaign' => 'Campagne',
    'campaign_description' => 'Rattachez cette réduction à une campagne pour partager son budget et ses limites d\'utilisation.',
    'campaign_helptext' => 'Les réductions d\'une campagne partagent le même budget et les mêmes limites d\'utilisation.',
    'campaign_locked_helper' => 'La campagne ne peut plus être modifiée après la première utilisation de la promotion, afin de préserver la cohérence du suivi de budget de la campagne.',
    'campaign_none' => 'Aucune campagne',

    'wizard' => [
        'type' => 'Type',
        'details' => 'Détails',
        'campaign' => 'Campagne',
    ],

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

    'create' => [
        'description' => 'Configurez un nouveau code de réduction. Le résumé à droite se met à jour pendant que vous remplissez le formulaire pour visualiser exactement ce que vos clients vont recevoir.',
    ],

    'edit' => [
        'description' => 'Mettez à jour cette réduction et consultez son utilisation réelle ainsi que son impact sur le revenu.',
    ],

    'sections' => [
        'general' => 'Général',
        'general_description' => 'Code, type et visibilité de la réduction.',
        'configuration' => 'Configuration',
        'configuration_description' => 'Combien de fois la réduction peut être utilisée et sa période de validité.',
        'targeting' => 'Ciblage',
        'targeting_description' => 'Quels produits et quels clients sont éligibles à cette réduction.',
        'combinations' => 'Combinaisons',
        'combinations_description' => 'Contrôlez le cumul de cette réduction avec les autres et sa priorité d\'évaluation.',
        'advanced' => 'Avancé',
        'advanced_description' => 'Métadonnées personnalisées attachées à la réduction.',
    ],

    'zone_frozen_helper' => 'La zone ne peut plus être modifiée après la première utilisation d\'une réduction à montant fixe. La cohérence de la devise est ainsi préservée sur les commandes existantes.',

    'summary' => [
        'title' => 'Résumé de la règle',
        'empty' => 'Choisissez un type et une valeur pour voir le résumé se mettre à jour en temps réel.',
        'uses_total' => 'utilisations max',
        'type_percentage' => ':value % de remise',
        'type_fixed_amount' => ':amount de remise',
        'minimum_price' => 'Panier ≥ :amount',
        'minimum_quantity' => 'Min :count article|Min :count articles',
        'visibility_public' => 'Public',
        'visibility_hidden' => 'Masqué',
        'rows' => [
            'type' => 'Type',
            'code' => 'Code',
            'zone' => 'Zone',
            'applies' => 'Cible',
            'for' => 'Pour',
            'minimum' => 'Minimum',
            'usage' => 'Utilisations',
            'usage_value' => '{1} :count utilisation max|[2,*] :count utilisations max',
            'active' => 'Actif',
            'visibility' => 'Visibilité',
        ],
    ],

    'stats' => [
        'title' => 'Performance',
        'usage' => 'Utilisations',
        'orders' => 'Commandes',
        'gross_revenue' => 'Revenu brut',
        'discount_cost' => 'Coût de la réduction',
        'aov_with' => 'Panier moyen avec ce code',
        'disclaimer' => 'Les statistiques incluent les commandes payées depuis la migration de suivi des réductions.',
    ],

    'actions' => [
        'duplicate' => 'Dupliquer',
        'duplicate_confirm_heading' => 'Dupliquer cette réduction ?',
        'duplicate_confirm_description' => 'Une copie sera créée avec un nouveau code suffixé `_COPY`, désactivée et avec un compteur d\'utilisations remis à zéro. Vous serez redirigé vers la nouvelle réduction pour terminer son édition.',
        'duplicate_in_progress' => 'Une duplication est déjà en cours.',
        'duplicate_success' => 'Réduction dupliquée sous le code :code.',
    ],

    'products_picker' => [
        'title' => 'Sélectionnez les produits concernés par la réduction',
        'description' => 'Choisissez un ou plusieurs produits. Ils apparaîtront dans le formulaire dès la confirmation.',
        'button' => 'Parcourir les produits',
        'bulk_add' => 'Ajouter les produits sélectionnés',
        'empty' => 'Aucun produit correspondant.',
        'empty_field' => 'Aucun produit sélectionné. Cliquez sur « Parcourir les produits » pour en ajouter.',
        'required' => 'Choisissez au moins un produit lorsque la réduction s\'applique à des produits spécifiques.',
    ],

    'customers_picker' => [
        'title' => 'Sélectionnez les clients éligibles à la réduction',
        'description' => 'Choisissez un ou plusieurs clients. Ils apparaîtront dans le formulaire dès la confirmation.',
        'button' => 'Parcourir les clients',
        'bulk_add' => 'Ajouter les clients sélectionnés',
        'empty' => 'Aucun client correspondant.',
        'empty_field' => 'Aucun client sélectionné. Cliquez sur « Parcourir les clients » pour en ajouter.',
        'required' => 'Choisissez au moins un client lorsque la réduction cible des clients spécifiques.',
    ],

    'apply_to_switch' => [
        'heading' => 'Passer à toute la commande ?',
        'description' => 'Vous avez choisi des produits spécifiques pour cette réduction. Passer à toute la commande les retirera de la sélection.',
        'submit' => 'Oui, basculer et vider',
        'cancel' => 'Garder les produits',
    ],

    'eligibility_switch' => [
        'heading' => 'Passer à tout le monde ?',
        'description' => 'Vous avez choisi des clients spécifiques. Passer à tout le monde les retirera de la sélection.',
        'submit' => 'Oui, basculer et vider',
        'cancel' => 'Garder les clients',
    ],

];
