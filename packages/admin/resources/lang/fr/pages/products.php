<?php

declare(strict_types=1);

return [

    'menu' => 'Produits',
    'single' => 'produit',
    'title' => 'Gérer le catalogue',
    'content' => 'Rapprochez-vous de votre première vente en ajoutant et en gérant des produits.',
    'about_pricing' => 'A propos des prix',
    'about_pricing_content' => 'Tous les prix sont en cents par défaut. Pour enregistrer 10€ (ou 10$), vous devez entrer 1000 cents pour que le formatage de la devise soit correct.',

    'amount_price_help_text' => 'Le prix d\'achat, avant les remises.',
    'compare_price_help_text' => 'Le prix de vente conseillé, pour comparaison avec le prix d\'achat. Ce prix est plus souvent supérieur',
    'cost_per_items_help_text' => 'Le  prix original de fabrication. Les clients ne le verront pas.',
    'safety_security_help_text' => 'Le stock de sécurité est le stock limite de vos produits qui vous alerte si le stock du produit est bientôt épuisé.',
    'quantity_inventory' => 'Inventaire des quantités',
    'manage_inventories' => 'Gérer les inventaires',
    'inventory_name' => 'Nom de l\'inventaire',
    'product_can_returned' => 'Ce produit peut être retourné',
    'product_can_returned_help_text' => 'Les utilisateurs ont la possibilité de retourner ce produit en cas de problème ou d\'insatisfaction.',
    'product_shipped' => 'Ce produit sera expédié',
    'product_shipped_help_text' => 'Renseignez les informations concernant l\'expédition du produit.',
    'general' => 'Information générale du produit',
    'status' => 'Disponibilité du produit',
    'visible_help_text' => 'Ce produit sera caché de tous les canaux de vente.',
    'availability_description' => 'Spécifiez une date de publication pour que vos produits soient programmés sur votre boutique.',
    'type' => 'Type de product',
    'product_type' => 'Définir comme Type par défaut',
    'product_type_helpText' => 'Cette configuration sera sauvegardée pour les prochains produits que vous allez créer.',
    'product_associations' => 'Catégorisation',
    'related_products' => 'Produits apparentés',
    'quantity_available' => 'Quantité disponible',
    'current_qty_inventory' => 'Quantité actuelle de cet inventaire',
    'stock_inventory_heading' => 'Stock & Inventaire',
    'stock_inventory_description' => "Configurer l'inventaire et le stock pour ce(tte) :item",
    'files_helpText' => 'Rajouter les fichiers qui seront téléchargeable à l\'achat de ce produit.',
    'images_helpText' => 'Ajouter des images à votre produit.',
    'variant_images_helpText' => 'Ajouter des images à votre variante.',
    'thumbnail_helpText' => 'Utilisé pour représenter votre produit lors du paiement, du partage social, et plus encore.',
    'weight_dimension' => 'Poids et dimensions',
    'weight_dimension_help_text' => 'Utilisé pour calculer les frais d\'expédition lors du passage à la caisse et pour étiqueter les prix lors du traitement des commandes.',
    'external_id_description' => 'L\'identifiant original de votre produit dans le service externe',
    'allow_backorder' => 'Autoriser les commandes différées',

    'modals' => [
        'title' => 'Supprimer ce/cette :item',
        'message' => 'Êtes-vous sûr de vouloir supprimer ce produit ? Toutes les informations associées à ce produit seront supprimées.',

        'variants' => [
            'title' => 'Gestion du stock pour cette variante',
            'select' => 'Sélectionner l\'inventaire',
            'add' => 'Ajouter une variante',
            'options' => [
                'title' => 'Attributs de la variante',
                'description' => 'Sélectionner les options d\'attributs pour cette variante.',
            ],
        ],
    ],

    'variants' => [
        'menu' => 'Variantes',
        'single' => 'variante',
        'title' => 'Variations de produits',
        'description' => 'Toutes les variantes de votre produit. Les variations peuvent avoir chacune leur stock et leur prix.',
        'add' => 'Ajouter une variante',
        'generate' => 'Générer les variantes',
        'generate_description' => 'Vos produits sont générés en fonction des attributs que vous avez sélectionnés',
        'variant_title' => 'Variantes ~ :name',
        'empty' => 'Aucune variante trouvée',
        'search_label' => 'Recherche de variantes',
        'search_placeholder' => 'Rechercher une variante du produit',
        'variant_information' => 'Informations sur la variante',
    ],

    'reviews' => [
        'title' => 'Avis des clients',
        'description' => 'C\'est là que vous verrez les avis de vos clients et les notes attribuées à vos produits.',
        'view' => 'Avis pour :product',
        'published' => 'Publié',
        'pending' => 'En attente',
        'approved' => 'Avis approuvé',
        'is_recommended' => 'Avis recommandé',
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
        'choose' => 'Sélectionner des attributs',
        'empty_title' => 'Aucun attribut activé',
        'empty_values' => 'Les attributs associés a ce produit seront listés ici.',

        'session' => [
            'delete' => 'Attribut supprimé',
            'delete_message' => 'Vous avez supprimé avec succès cet attribut du produit!',
            'delete_value' => 'Valeur d\'attribut supprimée',
            'delete_value_message' => 'Vous avez supprimé avec succès la valeur de cet attribut!',
            'added' => 'Attribut ajouté',
            'added_message' => 'Vous avez ajouté avec succès des attributs au produit',
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
        'add' => 'Ajout manuel',
        'remove' => 'Retrait manuel',
    ],

    'shipping' => [
        'description' => 'Informations sur le produit à retourner ou définir si le produit peut être envoyé au client.',
        'package_dimension' => 'Dimension de l\'emballage',
        'package_dimension_description' => 'Facturez des frais d\'expédition supplémentaires en fonction des dimensions des paquets couverts ici.',
    ],

    'related' => [
        'title' => 'Produits similaires',
        'description' => 'Tous les produits qui peuvent être identifiés comme similaires ou complémentaires à votre produit.',
        'empty' => 'Aucun produit similaire trouvé',
        'add_content' => 'Commencer par rajouter un produit connexe à votre produit.',

        'modal' => [
            'title' => 'Ajouter des produits connexes',
            'search' => 'Rechercher un produit',
            'search_placeholder' => 'Rechercher un produit par nom',
            'action' => 'Ajout produits sélectionnés',
            'success_message' => 'Produit(s) sélectionné(s) ajouté(s)',
            'no_results' => 'Aucun produit trouvé',
        ],
    ],

    'notifications' => [
        'files_update' => 'Les fichiers du produit ont été mis à jour.',
        'media_update' => 'Images du produit mise à jour!',
        'replicated' => 'Produit dupliqué!',
        'stock_update' => 'Le stock de produits a été mis à jour avec succès !',
        'seo_update' => 'Le référencement du produit a été mis à jour avec succès !',
        'shipping_update' => 'L\'expédition du produit a été mise à jour avec succès !',
        'variation_generate' => 'Variantes de produit sauvegardées avec succès',
        'variation_create' => 'La variante a été créée avec succès !',
        'variation_delete' => 'La variante a été supprimée avec succès !',
        'variation_update' => 'La variante a été mise à jour avec succès !',
        'related_added' => 'Le produit a été ajouté avec succès aux produits connexes !',
        'remove_related' => 'Le produit a été supprimé avec succès des produits connexes !',
        'manage_pricing' => 'La tarification de votre produit a été mis à jour !',
        'variant_already_exists' => 'Cette variante existe déjà',
    ],

    'pricing' => [
        'title' => 'Tarification du produit',
        'description' => 'Les différents prix associés à votre produit. Cela dépend du nombre de monnaie que vous avez dans votre boutique.',
        'add' => 'Ajouter une tarification',
        'empty' => 'Aucune tarification disponible',
    ],

];
