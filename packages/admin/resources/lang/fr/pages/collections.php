<?php

declare(strict_types=1);

return [

    'menu' => 'Collections',
    'single' => 'collection',
    'title' => 'Organisez vos produits en différentes collections',
    'content' => 'Créez et gérez toutes vos collections pour aider vos clients à trouver facilement des groupes ou des types de produits.',
    'automatic' => 'Automatique',
    'automatic_description' => 'Les produits qui correspondent aux conditions que vous avez définies seront automatiquement ajoutés à la collection.',
    'manual' => 'Manuel',
    'manual_description' => 'Ajoutez les produits à cette collection un par un.',
    'filter_type' => 'Type de Collection',
    'product_conditions' => 'Conditions du produit',
    'availability_description' => 'Précisez une date de publication pour que la collection soit programmée sur votre boutique.',
    'empty_collections' => 'Il n\'y a aucun produit dans cette collection. Ajoutez ou modifiez des conditions pour ajouter dynamiquement des produits.',
    'remove_product' => 'Le produit a été correctement retiré de cette collection.',

    'conditions' => [
        'title' => 'Conditions',
        'products_match' => 'Les produits doivent répondre',
        'all' => 'Toutes',
        'any' => 'N\'importe laquelle',
        'rules' => 'Règles',
        'choose_rule' => 'Choisissez une règle',
        'select_operator' => 'Sélectionnez l\'opérateur',
        'add' => 'Ajouter une condition',
        'add_another' => 'Ajouter une autre condition',
        'update' => 'Modification les conditions avec succès',
    ],

    'rules' => [
        'product_title' => 'Titre du Produit',
        'product_brand' => 'Marque du Produit',
        'product_category' => 'Catégorie du Produit',
        'product_price' => 'Prix du Produit',
        'compare_at_price' => 'Comparer au prix',
        'inventory_stock' => 'Stock d\'inventaire',
    ],

    'operator' => [
        'equals_to' => 'Égal à',
        'not_equals_to' => 'Pas égal à',
        'less_than' => 'Moins que',
        'greater_than' => 'Plus grand que',
        'starts_with' => 'Commence avec',
        'ends_with' => 'Termine par',
        'contains' => 'Contient',
        'not_contains' => 'Ne contient pas',
    ],

    'modal' => [
        'title' => 'Ajouter des produits à la collection',
        'search' => 'Rechercher un produit',
        'search_placeholder' => 'Rechercher un produit par nom',
        'action' => 'Ajout produits sélectionnés',
        'stock' => ':stock disponible',
        'success_message' => 'Produit(s) sélectionné(s) ajouté(s)',
    ],
];
