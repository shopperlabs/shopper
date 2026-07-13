<?php

declare(strict_types=1);

return [

    'menu' => 'Instrumentpanel',
    'welcome_message' => 'Välkommen till Shopper',
    'welcome_description' => 'Här är vad som behövs för att få igång butiken.',

    'cards' => [
        'doc_title' => 'Dokumentation',
    ],

    'guide' => [
        'title' => 'Installationsguide',
        'description' => 'Slutför dessa steg för att börja sälja.',
        'progress' => 'av :total slutförda',
        'dismiss' => 'Avvisa',
        'footer_hint' => 'Dessa inställningar kan alltid nås senare.',

        'steps' => [
            'add_product' => [
                'title' => 'Lägg till din första produkt',
                'description' => 'Lägg till produkter med priser, bilder och varianter för att börja bygga katalogen.',
                'action' => 'Lägg till en produkt',
            ],
            'create_collection' => [
                'title' => 'Skapa en kollektion',
                'description' => 'Organisera produkter i kollektioner för att göra det enkelt för kunder att bläddra i butiken.',
                'action' => 'Skapa en kollektion',
            ],
            'setup_zones' => [
                'title' => 'Ställ in fraktzoner',
                'description' => 'Konfigurera fraktzoner för att definiera var leverans sker och till vilken kostnad.',
                'action' => 'Ställ in frakt',
            ],
            'setup_payments' => [
                'title' => 'Ställ in betalsätt',
                'description' => 'Lägg till betalsätt so kunden kan betala för beställningar.',
                'action' => 'Ställ in betalsätt',
            ],
            'setup_taxes' => [
                'title' => 'Konfigurera moms',
                'description' => 'Ställ in momszoner och momssatser för att automatiskt beräkna moms på beställningar.',
                'action' => 'Konfigurera moms',
            ],
        ],
    ],

    'stats' => [
        'revenue' => 'Total omsättning',
        'products' => 'Totala produkter',
        'orders' => 'Totala beställningar',
        'customers' => 'Totala kunder',
        'vs_last_month' => 'mot föregående månad',
        'view_more' => 'Visa mer',
    ],

    'chart' => [
        'heading' => 'Prestanda',
        'series_label' => 'Omsättning',
    ],

    'recent_orders' => [
        'heading' => 'Senaste beställningar',
        'view_all' => 'Visa alla',
        'empty' => 'Inga beställningar ännu.',
    ],

    'top_products' => [
        'heading' => 'Mest sålda produkter',
        'view_all' => 'Visa alla',
        'product' => 'Produkt',
        'sales' => 'Försäljning',
        'reviews' => 'Recensioner',
        'empty' => 'Ingen försäljning ännu.',
    ],

    'addons' => [
        'title' => 'Utöka butiken',
        'badge' => 'Tillägg',
        'learn_more' => 'Läs mer',
        'configure' => 'Konfigurera transportörer',

        'stripe' => [
            'title' => 'Stripe',
            'description' => 'Acceptera kreditkort, Apple Pay och Google Pay med Stripe.',
        ],
        'carriers' => [
            'title' => 'Frakttransportörer',
            'description' => 'Anslut UPS, FedEx, USPS med flera för levande fraktpriser.',
        ],
    ],

];
