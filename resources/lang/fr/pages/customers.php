<?php

return [

    'title' => 'Gérer les commandes et les détails des clients',
    'content' => 'C\'est ici que vous pouvez gérer les informations de vos clients et consulter leur historique d\'achat.',

    'overview' => 'Aperçu du profil',
    'overview_description' => 'Utilisez une adresse permanente où le client peut recevoir du courrier.',
    'security_title' => 'Sécurité',
    'security_description' => 'Entrez un mot de passe aléatoire que cet utilisateur utilisera pour se connecter à son compte.',
    'address_title' => 'Adresse',
    'address_description' => 'L\'adresse principale de ce client. Cette adresse sera définie comme adresse de livraison par défaut.',
    'notification_title' => 'Notifications',
    'notification_description' => 'Informez le client de son compte.',
    'marketing_email' => 'Le client a accepté de recevoir des e-mails marketing.',
    'marketing_description' => 'Vous devez demander l\'autorisation au client avant de l\'abonner à vos e-mails marketing si vous en avez un.',
    'send_credentials' => 'Envoyer les informations d\'identification du client.',
    'credential_description' => 'Un email sera envoyé à ce client avec ces identifiants de connexion.',

    'period' => 'Client depuis :period',

    'modal' => [
        'title' => 'Archivé ce client',
        'description' => 'Voulez-vous vraiment désactiver ce client? Toutes ses données (commandes et adresses) seront définitivement supprimées de votre boutique pour toujours. Cette action ne peut pas être annulée.',
        'success_message' => 'Vous avez archivé ce client avec succès, il n\'est plus disponible dans votre liste de clients.',
    ],

    'profile' => [
        'title' => 'Profil',
        'description' => 'Toutes les informations publiques de vos clients peuvent être trouvées ici.',
        'account' => 'Compte',
        'account_description' => 'Gérer la façon dont les informations sont utilisées sur le compte client.',
        'marketing' => 'Publicité par e-mail',
        'two_factor' => 'Authentification à deux facteurs',
    ],

    'addresses' => [
        'title' => 'Adresses',
        'shipping' => 'Adresse de livraison',
        'billing' => 'Adresse de facturation',
        'default' => 'Adresse par défaut',
        'customer' => 'Adresses clients',
        'empty_text' => 'Ce client n\'a pas encore d\'adresse de livraison ou de facturation.',
    ],

    'orders' => [
        'placed' => 'Commande passée',
        'total' => 'Total',
        'ship_to' => 'Envoyez à',
        'order_number' => 'Commande :number',
        'details' => 'Détails de la commande',
        'items' => 'Éléments de la commande',
        'payment_method' => 'Mode de paiement',
        'shipping_method' => 'Mode de livraison',
        'no_shipping' => 'Pas de méthode d\'expédition',
        'estimated' => 'Délai de livraison estimé:',
        'view' => 'Voir commande',
        'empty_text' => 'Aucune commande trouvée...',
    ],

];
