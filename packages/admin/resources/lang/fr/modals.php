<?php

declare(strict_types=1);

return [
    'permissions' => [
        'new' => 'Nouvelle permission',
        'new_description' => 'Ajouter une nouvelle autorisation et l\'attribuer directement à ce rôle',
        'labels' => [
            'name' => 'Nom de la permission (en minuscules)',
        ],
    ],

    'roles' => [
        'new' => 'Ajouter un nouveau rôle',
        'new_description' => 'Ajouter un nouveau rôle et attribuer des autorisations aux administrateurs.',
        'labels' => [
            'name' => 'Nom (en minuscules)',
        ],
        'confirm_delete_msg' => 'Êtes-vous sûr de vouloir supprimer ce rôle ? Tous les utilisateurs qui avaient ce rôle ne pourront plus accéder aux actions données par ce rôle',
    ],

    'attributes' => [
        'new_value' => 'Ajouter une nouvelle valeur pour :attribute',
        'key_description' => 'La clé sera utilisée pour les valeurs stockées dans les formulaires (option, radio, etc.). Doit être au format slug',
        'update_value' => 'Mise à jour de la valeur de :name',
    ],

    'inventories' => [
        'confirm_delete_msg' => 'Êtes-vous sûr de vouloir supprimer cet inventaire ? Toutes ces données seront supprimées. Cette action ne peut être annulée',
    ],

    'mailable' => [
        'delete_title' => 'Supprimer :class Mailable',
        'confirm_delete_msg' => 'Êtes-vous sûr de vouloir supprimer cette classe Mailable ? Si cette classe est utilisée dans votre projet, cette action créera un bogue dans votre site.',
        'delete_template' => 'Supprimer :template Modèle',
        'confirm_delete_template' => 'Êtes-vous sûr de vouloir supprimer ce modèle ?',
    ],

    'payment_method' => [
        'update_title' => 'Mise à jour du mode de paiement',
    ],
];
