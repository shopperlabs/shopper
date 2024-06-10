<?php

declare(strict_types=1);

return [
    'permissions' => [
        'new' => 'Nouvelle permission',
        'new_description' => 'Ajouter une nouvelle autorisation et l\'attribuer directement à ce rôle',
        'labels' => [
            'name' => 'Permission (en minuscules)',
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

];
