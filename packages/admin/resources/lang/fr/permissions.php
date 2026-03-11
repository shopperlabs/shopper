<?php

declare(strict_types=1);

return [

    'system' => [
        'dashboard' => [
            'display_name' => 'Accès au tableau de bord',
            'description' => "Permet à l'utilisateur d'accéder au tableau de bord de l'administration.",
        ],
        'settings' => [
            'display_name' => 'Accès aux paramètres',
            'description' => "Permet à l'utilisateur de consulter et gérer les pages de paramètres.",
        ],
        'users' => [
            'display_name' => 'Voir les utilisateurs',
            'description' => "Permet à l'utilisateur d'accéder à la gestion de l'équipe et des rôles.",
        ],
    ],

    'generate' => [
        'browse' => [
            'display_name' => 'Parcourir :item',
            'description' => 'Permet de parcourir tous les enregistrements :item avec recherche, filtres et pagination.',
        ],
        'read' => [
            'display_name' => 'Lire :item',
            'description' => "Permet de consulter le détail complet d'un enregistrement :item.",
        ],
        'edit' => [
            'display_name' => 'Modifier :item',
            'description' => 'Permet de modifier et mettre à jour un enregistrement :item existant.',
        ],
        'create' => [
            'display_name' => 'Créer :item',
            'description' => 'Permet de créer un nouvel enregistrement :item.',
        ],
        'delete' => [
            'display_name' => 'Supprimer :item',
            'description' => 'Permet de supprimer définitivement un enregistrement :item.',
        ],
    ],

];
