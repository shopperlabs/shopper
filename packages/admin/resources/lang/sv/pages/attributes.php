<?php

declare(strict_types=1);

return [

    'menu' => 'Attribut',
    'single' => 'attribut',
    'title' => 'Hantera attribut',
    'content' => 'Lägg till anpassade attribut till produkter för att visa mer information',
    'add' => 'Lägg till attribut',
    'update' => 'Uppdatera attribut :attribute',
    'searchable_description' => 'Detta attribut kan användas för att söka och filtrera produkter.',
    'filtrable_description' => 'Detta attribut kan användas som filter i butiken.',
    'attribute_visibility' => 'Ställ in synlighet för attributet för kunder.',
    'attribute_value' => 'Attributvärde-ID',
    'description' => 'De attribut som är associerade med produkten. När de har valts kan dessa attribut kombineras för att generera en kombination av varianter.',

    'values' => [
        'slug' => 'Värden',
        'title' => 'Attributvärden',
        'description' => 'Lägg till standardvärden för detta attribut. Dessa värden kommer att vara tillgängliga på flikarna för produktattribut.',
    ],

    'notifications' => [
        'save' => 'Attributet har sparats',
        'value_created' => 'Nytt värde tillagt för :name',
        'value_updated' => 'Värdet har uppdaterats',
        'value_removed' => 'Värdet har tagits bort',
    ],

];
