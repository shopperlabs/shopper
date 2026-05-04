<?php

declare(strict_types=1);

return [

    'menu' => 'Clients',
    'single' => 'client',
    'title' => 'Gérer les commandes et les détails des clients',
    'description' => 'Parcourez les profils, suivez l\'activité au fil du temps et gérez chaque compte depuis un seul endroit.',
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

    'stats' => [
        'total' => 'Total clients',
        'total_subtitle' => 'Tous les comptes enregistrés',
        'new' => 'Nouveaux clients',
        'new_30_days' => 'sur les 30 derniers jours',
        'new_empty' => 'Aucun nouveau client sur 30 jours',
        'active' => 'Clients actifs',
        'active_subtitle' => 'ont passé au moins une commande payée',
        'active_empty' => 'Aucun client actif pour le moment',
        'avg_ltv' => 'Valeur moyenne sur la durée',
        'avg_ltv_subtitle' => 'Revenu moyen par client actif',
        'avg_ltv_empty' => 'En attente de la première commande payée',
    ],

    'header' => [
        'since' => 'Client depuis le :date',
        'orders_count' => '{0} aucune commande|{1} :count commande|[2,*] :count commandes',
        'id' => 'ID client #:id',
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'previous' => 'Client précédent',
        'next' => 'Client suivant',
        'last_order' => 'Dernière commande :time',
    ],

    'details' => [
        'title' => 'Informations client',
        'id' => 'ID client',
        'copy_id' => 'Copier l\'ID client',
        'copied' => 'Copié dans le presse-papiers',
        'created' => 'Créé le',
        'email_status' => 'Email',
        'email_verified' => 'Vérifié',
        'email_unverified' => 'Non vérifié',
        'marketing_on' => 'Abonné',
        'marketing_off' => 'Désabonné',
        'two_factor_on' => 'Activé',
        'two_factor_off' => 'Désactivé',
    ],

    'contact' => [
        'title' => 'Coordonnées',
        'no_phone' => 'Aucun numéro de téléphone enregistré',
    ],

    'default_address' => [
        'title' => 'Adresse par défaut',
        'empty' => 'Ce client n\'a aucune adresse enregistrée.',
    ],

    'create' => [
        'description' => 'Créez un compte client, définissez ses identifiants et envoyez-lui un e-mail de bienvenue avec ses informations de connexion.',
    ],

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
        'marketing' => 'E-mails marketing',
        'two_factor' => 'Authentification 2FA',
    ],

    'addresses' => [
        'title' => 'Adresses',
        'shipping' => 'Adresse de livraison',
        'billing' => 'Adresse de facturation',
        'shipping_section' => 'Adresses de livraison',
        'billing_section' => 'Adresses de facturation',
        'default' => 'Par défaut',
        'customer' => 'Adresses clients',
        'empty_text' => 'Ce client n\'a pas encore d\'adresse de livraison ou de facturation.',
        'shipping_empty_title' => 'Aucune adresse de livraison',
        'shipping_empty' => 'Ce client n\'a pas encore enregistré d\'adresse de livraison.',
        'billing_empty_title' => 'Aucune adresse de facturation',
        'billing_empty' => 'Ce client n\'a pas encore enregistré d\'adresse de facturation.',
    ],

    'orders' => [
        'placed' => 'Commande passée',
        'total' => 'Total',
        'ship_to' => 'Envoyez à',
        'order_number' => 'Commande :number',
        'details' => 'N° de la commande',
        'items' => 'Produits de la commande',
        'view' => 'Voir commande',
        'empty_text' => 'Aucune commande trouvée...',
        'no_shipping' => 'Aucun moyen de livraison',
        'estimated' => 'Date de livraison',
    ],

    'anonymize' => [
        'action' => 'Anonymiser le client',
        'title' => 'Anonymiser ce client',
        'description' => 'Cette action anonymisera définitivement toutes les données personnelles de ce client (nom, email, téléphone, adresses). L\'historique des commandes sera conservé à des fins comptables. Cette action est irréversible.',
        'confirm' => 'Oui, anonymiser',
        'success' => 'Le client a été anonymisé avec succès.',
        'first_name' => 'Client',
        'last_name' => 'Supprimé',
    ],

];
