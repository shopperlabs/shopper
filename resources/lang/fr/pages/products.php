<?php

return [

    'title' => 'Manage Catalog',
    'content' => 'Get closer to your first sale by adding and manage products.',

    'cost_per_items_help_text' => 'Customers won’t see this.',
    'safety_security_help_text' => 'The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.',
    'quantity_inventory' => 'Quantity Inventory',
    'manage_inventories' => 'Manage Inventories',
    'inventory_name' => 'Inventory name',
    'product_can_returned' => 'This product can be returned',
    'product_can_returned_help_text' => 'Users have the option of returning this product if there is a problem or dissatisfaction.',
    'product_shipped' => 'This product will be shipped',
    'product_shipped_help_text' => 'Reassure to fill in the information concerning the shipment of the product.',
    'weight_dimension_help_text' => 'Used to calculate shipping charges during checkout and to label prices during order processing.',
    'status' => 'Product status',
    'visible_help_text' => 'This product will be hidden from all sales channels.',
    'availability_description' => 'Specify a publication date so that your product are scheduled on your store.',
    'product_associations' => 'Product associations',
    'product_categories' => 'Product categories',
    'no_category' => 'No Categories',
    'no_category_text' => 'Get started by creating a new category.',
    'new_category' => 'New category',
    'related_products' => 'Related Products',
    'quantity_available' => 'Quantity Available',
    'current_qty_inventory' => 'Current quantity on this inventory',

    'modals' => [
        'title' => 'Delete this :item',
        'message' => 'Are you sure you want to delete this product? All information associated with this product will be deleted.',

        'variants' => [
            'title' => 'Stock management for this variant',
            'select' => 'Select inventory',
        ],
    ],

    'variants' => [
        'title' => 'Products variations',
        'description' => 'All variations of your product. The variations can each have their stock and price.',
        'add' => 'Add variant',
        'empty' => 'No variant found',
        'search_label' => 'Search variant',
        'search_placeholder' => 'Search product variant',
        'variant_information' => 'Variant information',

        'actions' => [
            'update' => 'Update variant',
            'update_stock' => 'Update stock',
            'manage_inventory' => 'Manage Inventories',
        ],
    ],

    'reviews' => [
        'title' => 'Customers reviews',
        'description' => 'This is where you will see the reviews of your customers and the ratings given to your products.',
        'view' => 'Reviews for :product',
        'published' => 'Published',
        'pending' => 'Pending',
        'approved' => 'Approved Review',
        'approved_status' => 'Approved status',
        'approved_message' => 'Mise à jour de l\'avis approuvée !',

        'subtitle' => 'Review for this product.',
        'reviewer' => 'Reviewer',
        'review' => 'Review',
        'review_content' => 'Content',
        'status' => 'Status',
        'rating' => 'Rating',

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
        'variation_delete' => 'La variation a été supprimée avec succès !',
        'variation_update' => 'La variante a été mise à jour avec succès !',
    ],

];
