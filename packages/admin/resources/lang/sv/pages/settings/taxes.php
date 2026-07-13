<?php

declare(strict_types=1);

return [

    'title' => 'Moms',
    'single' => 'Momszon',
    'description' => 'Hantera momszoner, skattesatser och momsbeteende för din butik.',
    'add_action' => 'Lägg till momszon',
    'empty_heading' => 'Inga momszoner',
    'empty_detail_heading' => 'Ingen momszon vald',
    'empty_detail_description' => 'Välj en momszon för att visa dess detaljer och satser.',
    'inclusive' => 'Inkl. moms',
    'exclusive' => 'Exkl. moms',
    'inclusive_help' => 'Aktivera för prissättning inklusive moms (t.ex. Europa, Afrika).',
    'tax_behavior' => 'Momsbeteende',
    'provider' => 'Momsleverantör',
    'system_default' => 'System (standard)',
    'province_code' => 'Läns- / Regionkod',
    'province_code_help' => 'ISO 3166-2 indelningskod (t.ex. SE-AB, US-CA).',
    'name_help' => 'Valfritt visningsnamn för denna zon (t.ex. Kalifornien, Stockholm).',

    'rates' => [
        'title' => 'Momssatser',
        'add' => 'Lägg till momssats',
        'add_heading' => 'Momssats för :name',
        'update' => 'Uppdatera :name',
        'rate' => 'Sats',
        'empty_heading' => 'Inga momssatser konfigurerade',
        'default_help' => 'Använd denna sats när inget produktspecifikt undantag gäller.',
        'combinable' => 'Kombinerbar',
        'combinable_help' => 'Tillåt att denna skattesats staplas med överordnade zonsatser.',
    ],

    'overrides' => [
        'add' => 'Skapa undantag',
        'add_heading' => 'Momsundantag för :name',
        'update' => 'Uppdatera undantag :name',
        'description' => 'Ett undantag tillämpar en annan momssats för specifika produkter, produkttyper eller kategorier.',
        'targets' => 'Mål',
        'targets_help' => 'Välj vilka produkter, produkttyper eller kategorier detta undantag gäller för.',
        'target_type' => 'Måltyp',
        'target_value' => 'Målvärde',
        'add_target' => 'Lägg till mål',
        'product_types' => 'Produkttyper',
        'products' => 'Produkter',
        'categories' => 'Kategorier',
    ],

];
