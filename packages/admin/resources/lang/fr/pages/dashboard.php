<?php

declare(strict_types=1);

return [

    'menu' => 'Tableau de bord',
    'welcome_message' => 'Bienvenue sur Shopper',
    'welcome_description' => 'Voici ce dont vous avez besoin pour lancer votre boutique.',

    'cards' => [
        'doc_title' => 'Documentation',
    ],

    'guide' => [
        'title' => 'Guide de configuration',
        'description' => 'Complétez ces étapes pour commencer à vendre.',
        'progress' => 'sur :total complétées',
        'dismiss' => 'Masquer',
        'footer_hint' => 'Vous pouvez toujours accéder à ces paramètres plus tard.',

        'steps' => [
            'add_product' => [
                'title' => 'Ajouter votre premier produit',
                'description' => 'Ajoutez des produits avec des prix, des images et des variantes pour commencer à construire votre catalogue.',
                'action' => 'Ajouter un produit',
            ],
            'create_collection' => [
                'title' => 'Créer une collection',
                'description' => 'Organisez vos produits en collections pour faciliter la navigation de vos clients.',
                'action' => 'Créer une collection',
            ],
            'setup_zones' => [
                'title' => 'Configurer les zones de livraison',
                'description' => 'Configurez vos zones de livraison pour définir où vous livrez et à quel coût.',
                'action' => 'Configurer la livraison',
            ],
            'setup_payments' => [
                'title' => 'Configurer les moyens de paiement',
                'description' => 'Ajoutez des moyens de paiement pour que vos clients puissent payer leurs commandes.',
                'action' => 'Configurer les paiements',
            ],
            'setup_taxes' => [
                'title' => 'Configurer les taxes',
                'description' => 'Configurez les zones et taux de taxes pour calculer automatiquement les taxes sur les commandes.',
                'action' => 'Configurer les taxes',
            ],
        ],
    ],

    'stats' => [
        'revenue' => 'Revenu total',
        'products' => 'Total produits',
        'orders' => 'Total commandes',
        'customers' => 'Total clients',
        'vs_last_month' => 'vs mois dernier',
        'view_more' => 'Voir plus',
    ],

    'chart' => [
        'heading' => 'Performance',
        'series_label' => 'Revenus',
    ],

    'recent_orders' => [
        'heading' => 'Commandes récentes',
        'view_all' => 'Voir tout',
        'empty' => 'Aucune commande pour le moment.',
    ],

    'top_products' => [
        'heading' => 'Produits les plus vendus',
        'view_all' => 'Voir tout',
        'product' => 'Produit',
        'sales' => 'Ventes',
        'reviews' => 'Avis',
        'empty' => 'Aucune vente pour le moment.',
    ],

    'addons' => [
        'title' => 'Étendre votre boutique',
        'badge' => 'Add-on',
        'learn_more' => 'En savoir plus',
        'configure' => 'Configurer les transporteurs',

        'stripe' => [
            'title' => 'Stripe',
            'description' => 'Acceptez les cartes bancaires, Apple Pay et Google Pay avec Stripe.',
        ],
        'carriers' => [
            'title' => 'Transporteurs',
            'description' => 'Connectez UPS, FedEx, USPS et plus pour des tarifs en temps réel.',
        ],
    ],

];
