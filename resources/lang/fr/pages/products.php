<?php

return [

    'title' => 'Gérer le catalogue',
    'content' => 'Rapprochez-vous de votre première vente en ajoutant et en gérant des produits.',

    'cost_per_items_help_text' => 'Les clients ne le verront pas.',
    'safety_security_help_text' => 'Le stock de sécurité est le stock limite de vos produits qui vous alerte si le stock du produit est bientôt épuisé.',
    'quantity_inventory' => 'Inventaire des quantités',
    'manage_inventories' => 'Gérer les inventaires',
    'inventory_name' => 'Nom de l\'inventaire',
    'product_can_returned' => 'Ce produit peut être retourné',
    'product_can_returned_help_text' => 'Les utilisateurs ont la possibilité de retourner ce produit en cas de problème ou d\'insatisfaction.',
    'product_shipped' => 'Ce produit sera expédié',
    'product_shipped_help_text' => 'Renseignez les informations concernant l\'expédition du produit.',
    'weight_dimension_help_text' => 'Utilisé pour calculer les frais d\'expédition lors du passage à la caisse et pour étiqueter les prix lors du traitement des commandes.',
    'status' => 'Statut du produit',
    'visible_help_text' => 'Ce produit sera caché de tous les canaux de vente.',
    'availability_description' => 'Spécifiez une date de publication pour que vos produits soient programmés sur votre boutique.',
    'product_associations' => 'Associations de produits',
    'product_categories' => 'Catégories du produit',
    'no_category' => 'Aucune catégorie',
    'no_category_text' => 'Commencez par créer une nouvelle catégorie.',
    'new_category' => 'Nouvelle catégorie',
    'related_products' => 'Produits apparentés',
    'quantity_available' => 'Quantité disponible',
    'current_qty_inventory' => 'Quantité actuelle de cet inventaire',

    'modals' => [
        'title' => 'Supprimer ce(tte) :item',
        'message' => 'Êtes-vous sûr de vouloir supprimer ce produit ? Toutes les informations associées à ce produit seront supprimées.',

        'variants' => [
            'title' => 'Gestion du stock pour cette variante',
            'select' => 'Sélectionner l\'inventaire',
        ],
    ],

    'variants' => [
        'title' => 'Variations de produits',
        'description' => 'Toutes les variantes de votre produit. Les variations peuvent avoir chacune leur stock et leur prix.',
        'add' => 'Ajouter une variante',
        'empty' => 'Aucune variante trouvée',
        'search_label' => 'Recherche de variantes',
        'search_placeholder' => 'Rechercher une variante du produit',
        'variant_information' => 'Informations sur la variante',

        'actions' => [
            'update' => 'Variante de mise à jour',
            'update_stock' => 'Mise à jour du stock',
            'manage_inventory' => 'Gérer les inventaires',
        ],

        'modal' => [
            'title' => 'À propos de la variation',
            'description' => 'Nom et prix de la variante. Si le prix est vide, le prix du produit sera appliqué.',
        ],
    ],

    'reviews' => [
        'title' => 'Avis des clients',
        'description' => 'C\'est là que vous verrez les avis de vos clients et les notes attribuées à vos produits.',
        'view' => 'Avis pour :product',
        'published' => 'Publié',
        'pending' => 'En attente',
        'approved' => 'Avis approuvé',
        'approved_status' => 'Statut approuvé',
        'approved_message' => 'Mise à jour de l\'avis approuvée !',

        'subtitle' => 'Avis sur ce produit.',
        'reviewer' => 'Réviseur',
        'review' => 'Avis',
        'review_content' => 'Contenu',
        'status' => 'Status',
        'rating' => 'Note',

        'modal' => [
            'title' => 'Supprimer cet avis',
            'description' => 'Voulez-vous vraiment supprimer cet avis? Cet avis ne pourra plus être récupéré.',
            'success_message' => 'Avis supprimé avec succès !',
        ],
    ],

    'attributes' => [
        'title' => 'Attributs de produit',
        'description' => 'Tous les attributs associés à ce produit.',
        'add' => 'Ajouter attribut',
        'empty_values' => 'Aucun attributs',

        'session' => [
            'delete' => 'Attribut supprimé',
            'delete_message' => 'Vous avez supprimé avec succès cet attribut du produit!',
            'delete_value' => 'Valeur d\'attribut supprimée',
            'delete_value_message' => 'Vous avez supprimé avec succès la valeur de cet attribut!',
            'added' => 'Attribut ajouté',
            'added_message' => 'Vous avez ajouté avec succès un attribut à ce produit!',
        ],

        'modals' => [
            'title' => 'Ajouter un nouvel attribut avec une valeur',
            'input_placeholder' => 'Sélectionnez l\'attribut à ajouter',
        ],
    ],

    'inventory' => [
        'title' => 'Attributs de l\'inventaire',
        'description' => 'Champs relatifs à la gestion des stocks dans votre magasin.',
        'stock_title' => 'Gestion du stock',
        'stock_description' => 'Gestion des stocks dans vos différents inventaires.',
        'empty' => 'Aucun ajustement n\'a été effectué sur l\'inventaire.',
        'movement' => 'Mouvement des quantités',
        'initial' => 'Stock initial',
    ],

    'seo' => [
        'title' => 'Optimisation des moteurs de recherche',
        'description' => 'Améliorez votre classement et la façon dont votre page produit apparaîtra dans les résultats des moteurs de recherche.',
        'sub_description' => 'Voici un aperçu du résultat de votre moteur de recherche, jouez avec !',
    ],

    'shipping' => [
        'description' => 'Informations sur le produit à retourner ou définir si le produit peut être envoyé au client.',
        'package_dimension' => 'Dimension de l\'emballage',
        'package_dimension_description' => 'Facturez des frais d\'expédition supplémentaires en fonction des dimensions des paquets couverts ici.',
    ],

    'related' => [
        'title' => 'Produits similaires',
        'description' => 'Tous les produits qui peuvent être identifiés comme similaires ou complémentaires à votre produit.',
    ],

    'notifications' => [
        'create' => 'Produit ajouté avec succès !',
        'update' => 'Produit mis à jour avec succès !',
        'stock_update' => 'Le stock de produits a été mis à jour avec succès !',
        'seo_update' => 'Le référencement du produit a été mis à jour avec succès !',
        'shipping_update' => 'L\'expédition du produit a été mise à jour avec succès !',
        'variation_create' => 'La variation du produit a été ajoutée avec succès !',
        'variation_delete' => 'La variation a été supprimée avec succès !',
        'variation_update' => 'La variante a été mise à jour avec succès !',
    ],

];
