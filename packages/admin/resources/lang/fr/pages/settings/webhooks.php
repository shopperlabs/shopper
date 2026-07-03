<?php

declare(strict_types=1);

return [
    'title' => 'Webhooks',
    'add_webhook' => 'Ajouter un webhook',
    'no_webhook' => 'Aucun abonnement webhook pour le moment. Ajoutez-en un pour notifier un système externe lorsque des événements se produisent dans votre boutique.',
    'events' => 'Événements',
    'deliveries' => 'Livraisons',
    'no_delivery' => 'Aucune livraison pour ce webhook.',
    'attempt' => 'Tentative :number',
    'redeliver' => 'Renvoyer',
    'redelivered' => 'La livraison a été remise en file d\'attente.',
    'redeliver_refused' => 'La ressource de cet événement n\'existe plus, le payload ne peut pas être renvoyé.',
    'created' => 'Webhook créé',
    'updated' => 'Webhook mis à jour',
    'unsafe_url' => 'L\'url du webhook doit utiliser https et pointer vers une adresse publique.',
    'regenerate_secret' => 'Regénérer le secret',
    'regenerate_secret_warning' => 'Le secret actuel cesse de fonctionner immédiatement. Mettez à jour votre endpoint avec le nouveau.',
    'secret_regenerated' => 'Secret regénéré',
    'secret_reveal' => 'Secret de signature (copiez-le maintenant, il ne sera plus affiché) : :secret',
    'cap_reached' => 'Le nombre maximum d\'abonnements webhook est atteint.',
];
