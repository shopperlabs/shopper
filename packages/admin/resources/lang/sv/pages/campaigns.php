<?php

declare(strict_types=1);

return [

    'menu' => 'Kampanjer',
    'single' => 'kampanj',
    'title' => 'Hantera marknadsföringskampanjer',
    'description' => 'Gruppera erbjudanden under en kampanj med en valfri spenderings- eller användningsbudget.',

    'name' => 'Namn',
    'name_placeholder' => 'Sommarrea 2026',
    'currency' => 'Valuta',
    'budget_type' => 'Budgettyp',
    'budget_amount' => 'Budgetbelopp',
    'budget_count' => 'Användningsbudget',
    'budget' => 'Budget',
    'promotions' => 'Kampanjerbjudanden',
    'no_budget' => 'Ingen budget',
    'currency_frozen_helper' => 'Valutan kan inte ändras när kampanjen har registrerat budgetrörelser.',

    'create' => [
        'description' => 'Konfigurera en ny kampanj. Sammanfattningen till höger uppdateras när formuläret fylls i.',
    ],

    'edit' => [
        'description' => 'Uppdatera kampanjen och granska dess budgetförbrukning.',
    ],

    'sections' => [
        'general' => 'Allmänt',
        'general_description' => 'Namn, valuta och synlighet för kampanjen.',
        'budget' => 'Budget',
        'budget_description' => 'Begränsa hur mycket denna kampanj kan spendera eller hur många gånger den kan användas.',
        'schedule' => 'Schema',
        'schedule_description' => 'När kampanjen startar och slutar.',
        'advanced' => 'Avancerat',
        'advanced_description' => 'Anpassad metadata kopplad till kampanjen.',
    ],

    'summary' => [
        'title' => 'Kampanjsammanfattning',
        'empty' => 'Fyll i formuläret för att se sammanfattningen uppdateras i realtid.',
        'rows' => [
            'name' => 'Namn',
            'currency' => 'Valuta',
            'budget' => 'Budget',
            'schedule' => 'Schema',
            'visibility' => 'Synlighet',
        ],
        'visibility_public' => 'Aktiv',
        'visibility_hidden' => 'Inaktiverad',
    ],

    'budget_panel' => [
        'title' => 'Budgetförbrukning',
        'spend' => 'Belopp',
        'count' => 'Användning',
        'none' => 'Kampanjen har ingen budgetbegränsning.',
    ],

    'promotions_panel' => [
        'title' => 'Kopplade erbjudanden',
        'empty' => 'Inga erbjudanden är kopplade till denna kampanj ännu.',
    ],

    'save' => 'Kampanjen :name har sparats!',

];
