<?php

declare(strict_types=1);

return [

    'menu' => 'Avis',
    'single' => 'avis',
    'title' => 'Avis clients',
    'description' => 'Découvrez ce que vos clients pensent de vos produits.',

    'stats' => [
        'average' => 'Note moyenne',
        'based_on' => 'Basé sur :count avis',
        'total' => 'Avis au total',
        'last_30_days' => 'sur les 30 derniers jours',
        'no_recent' => 'Aucun nouvel avis sur les 30 derniers jours',
        'no_data' => 'Aucun avis pour le moment',
        'five_star' => 'Avis 5 étoiles',
        'excellent' => 'Avis excellents',
        'pending' => 'En attente de modération',
        'pending_description' => 'Action requise',
        'pending_empty' => 'Tout est traité',
    ],

    'breakdown' => [
        'title' => 'Répartition des notes',
        'description' => 'Comment vos clients vous notent',
    ],

    'recommended' => [
        'title' => 'Recommandation',
        'description' => ':percent% de clients vous recommandent',
        'empty' => 'Pas encore de recommandation',
    ],

    'tabs' => [
        'all' => 'Tous',
        'pending' => 'En attente',
        'approved' => 'Approuvés',
    ],

    'actions' => [
        'approve' => 'Approuver',
        'reject' => 'Rejeter',
        'mark_as_spam' => 'Marquer comme spam',
        'approved_message' => 'Avis approuvé.',
        'rejected_message' => 'Avis rejeté.',
        'spam_message' => 'Avis signalé comme spam.',
        'spam_confirmation' => 'Signaler cet avis comme spam le masquera à vos clients. Vous pourrez l\'annuler depuis la file de modération.',
    ],

];
