<?php

declare(strict_types=1);

return [

    'title' => 'Taxes',
    'single' => 'Zone fiscale',
    'description' => 'Gérez les zones fiscales, les taux et le comportement des taxes pour votre boutique.',
    'add_action' => 'Ajouter une zone fiscale',
    'empty_heading' => 'Aucune zone fiscale',
    'empty_detail_heading' => 'Aucune zone fiscale sélectionnée',
    'empty_detail_description' => 'Sélectionnez une zone fiscale pour voir ses détails et ses taux.',
    'inclusive' => 'TTC',
    'exclusive' => 'HT',
    'inclusive_help' => 'Activer pour la tarification TTC (ex. Europe, Afrique).',
    'tax_behavior' => 'Comportement fiscal',
    'provider' => 'Fournisseur de taxes',
    'system_default' => 'Système (par défaut)',
    'province_code' => 'Code province / état',
    'province_code_help' => 'Code de subdivision ISO 3166-2 (ex. US-CA, FR-IDF, GB-ENG).',
    'name_help' => "Nom d'affichage optionnel pour cette zone (ex. Californie, Île-de-France).",

    'rates' => [
        'title' => 'Taux de taxation',
        'add' => 'Ajouter un taux',
        'add_heading' => 'Taux de taxation pour :name',
        'update' => 'Modifier :name',
        'rate' => 'Taux',
        'empty_heading' => 'Aucun taux configuré',
        'default_help' => "Utiliser ce taux lorsqu'aucune dérogation spécifique au produit ne s'applique.",
        'combinable' => 'Cumulable',
        'combinable_help' => 'Permettre à ce taux de se cumuler avec les taux de la zone parente.',
    ],

    'overrides' => [
        'add' => 'Créer une dérogation',
        'add_heading' => 'Dérogation de taux pour :name',
        'update' => 'Modifier la dérogation :name',
        'description' => 'Une dérogation applique un taux de taxation différent à des produits, types de produits ou catégories spécifiques.',
        'targets' => 'Cibles',
        'targets_help' => 'Sélectionnez les produits, types de produits ou catégories auxquels cette dérogation s\'applique.',
        'target_type' => 'Type de cible',
        'target_value' => 'Valeur de la cible',
        'add_target' => 'Ajouter une cible',
        'product_types' => 'Types de produits',
        'products' => 'Produits',
        'categories' => 'Catégories',
    ],

];
