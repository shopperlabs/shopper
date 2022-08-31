<?php

return [

    'empty_country_selector' => 'Veuillez sélectionner un pays',
    'logo_description' => 'Le logo de votre boutique qui sera visible sur votre site. Cet actif apparaîtra sur vos factures.',
    'about_description' => 'Vous pouvez consulter ces informations sur la page "À propos" de votre site web.',
    'currency_description' => 'Il s\'agit de la devise dans laquelle vos produits sont vendus. Après votre première vente, la devise est verrouillée et ne peut être modifiée.',
    'mapbox_disabled' => 'Mapbox n\'a pas été activée.',

    'initialization' => [
        'step_one_title' => 'Information de la boutique',
        'step_one_description' => 'Fournissez des informations utiles pour votre magasin.',
        'step_two_title' => 'Informations sur l\'adresse',
        'step_two_description' => 'Indiquez l\'adresse du magasin.',
        'step_tree_title' => 'Liens sociaux (optionnel)',
        'step_tree_description' => 'Liens vers vos comptes de médias sociaux.',

        'step' => 'Étape :step sur 3',
        'shop_configuration' => 'Configuration de la boutique',
        'step_1' => 'Étape 1 - Informations sur le magasin',
        'tell_about' => 'Parlez-nous de votre boutique',
        'step_1_description' => 'Ces informations seront utiles si vous souhaitez que les utilisateurs de votre site vous contactent directement par e-mail ou par votre numéro de téléphone.',

        'step_2' => 'Étape 2 - Informations sur l\'adresse',
        'address_description' => 'Vous devez préciser l\'adresse et la localisation de votre magasin',
        'step_2_description' => 'Ne vous inquiétez pas. Vous pouvez modifier ces paramètres à tout moment. Shopper vous permet de commencer par le plus petit niveau afin que vous puissiez voir l\'évolution de votre boutique.',

        'step_3' => 'Étape 3 - Liens sociaux',
        'social_description' => 'Votre boutique sur les réseaux sociaux.',
        'step_3_description' => 'Vous pouvez ajouter des liens vers vos comptes de médias sociaux afin que votre boutique puisse être trouvée facilement sur vos pages de médias sociaux.',
        'action' => 'Configurer ma boutique',
    ],

    'settings' => [
        'title' => 'Réglage du magasin',
    ],

    'payment' => [
        'stripe_description' => 'Acceptez les paiements sur votre boutique en utilisant des fournisseurs tiers tels que Stripe.',
        'stripe_enabled' => 'Stripe est disponible pour votre magasin.',
        'stripe_disabled' => 'Stripe n\est pas activé.',
        'stripe_provider' => 'Ce fournisseur vous permet d\'intégrer Stripe PHP dans votre boutique pour permettre à vos clients d\'effectuer des paiements.',
        'stripe_about' => 'En savoir plus sur Stripe Payment',
        'stripe_actions' => 'Activer le paiement Stripe',
        'stripe_environment' => 'Stripe dispose de deux environnements: Sandbox et Live. Veillez à utiliser le sandbox pour les tests avant de passer à l\'action.',
        'stripe_dashboard' => 'Les clés d\'API peuvent être récupérées à partir de',
    ],

    'validations' => [
        'shop_name' => 'Le nom du magasin est obligatoire',
        'country' => 'Le pays est requis',
    ],

    'notifications' => [
        'email_config' => 'Vos configurations de messagerie ont été correctement mises à jour !',
        'stripe' => 'La configuration de vos paiements Stripe a été correctement mise à jour !',
        'stripe_enable' => 'Vous avez activé avec succès le paiement Stripe pour votre boutique !',
    ],
];
