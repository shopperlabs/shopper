<?php

declare(strict_types=1);

return [

    'cart_completed' => 'Varukorgen har redan slutförts.',
    'cart_not_found' => 'Varukorgen hittades inte.',
    'cart_owned_by_another_customer' => 'Den här varukorgen tillhör en annan kund.',
    'payment_session_collected' => 'Varukorgen har redan betalats. Slutför köpet för att lägga ordern.',
    'insufficient_stock' => 'Otillräckligt lager för denna artikel.',
    'price_changed' => 'Priset på en artikel i varukorgen har ändrats. Vänligen granska varukorgen före kassan.',
    'missing_price' => 'Den här artikeln har inget pris i :currency.',
    'quantity_minimum' => 'Antalet måste vara minst 1.',
    'line_locked' => 'Den här artikeln har ett förhandlat pris och antalet kan inte ändras.',
    'line_metadata_conflict' => 'Den här artikeln finns redan i varukorgen med andra metadata: ändra den raden eller ta bort den först.',
    'price_negative' => 'Ett pris kan inte vara negativt.',
    'quantity_rule' => [
        'minimum' => 'Den här artikeln måste beställas i minst :minimum exemplar.',
        'maximum' => 'Den här artikeln kan inte beställas i fler än :maximum exemplar.',
        'increment' => 'Den här artikeln måste beställas i multiplar av :increment.',
    ],
    'discount_not_found' => 'Rabattkoden hittades inte.',

    'discount_limit' => [
        'global' => 'Rabatten ":code" har uppnått sin användningsgräns och kan inte längre tillämpas.',
        'per_user' => 'Rabatten ":code" har redan använts och är begränsad till en användning per kund.',
        'automatic' => 'automatisk kampanj',
    ],

];
