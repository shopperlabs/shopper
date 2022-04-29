<?php

return [

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

];
