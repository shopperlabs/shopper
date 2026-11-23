<?php

declare(strict_types=1);

return [
    'admin' => [
        'display_name' => 'Administrateur',
        'description' => 'Administrateur du site ayant accès au panneau d\'administration de la boutique et aux outils de développement.',
    ],
    'manager' => [
        'display_name' => 'Responsable',
        'description' => 'Responsable de la boutique avec accès au catalogue, aux commandes, aux réductions et aux clients. Les paramètres et la gestion de l\'équipe restent réservés aux administrateurs.',
    ],
    'user' => [
        'display_name' => 'Utilisateur',
        'description' => 'Rôle de client du site avec accès au site internet.',
    ],
];
