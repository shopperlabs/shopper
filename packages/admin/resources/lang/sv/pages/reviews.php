<?php

declare(strict_types=1);

return [

    'menu' => 'Recensioner',
    'single' => 'recension',
    'title' => 'Kundrecensioner',
    'description' => 'Se vad kunder säger om produkterna.',

    'stats' => [
        'average' => 'Genomsnittligt betyg',
        'based_on' => 'Baserat på :count recensioner',
        'total' => 'Totala recensioner',
        'last_30_days' => 'under de senaste 30 dagarna',
        'no_recent' => 'Inga nya recensioner under de senaste 30 dagarna',
        'no_data' => 'Inga recensioner ännu',
        'five_star' => '5-stjärniga recensioner',
        'excellent' => 'Utmärkta betyg',
        'pending' => 'Väntar på moderering',
        'pending_description' => 'Kräver åtgärd',
        'pending_empty' => 'Inkorgen är tom',
    ],

    'breakdown' => [
        'title' => 'Betygsfördelning',
        'description' => 'Hur kunderna sätter betyg',
    ],

    'recommended' => [
        'title' => 'Rekommenderad',
        'description' => ':percent% av kunderna rekommenderar',
        'empty' => 'Inga rekommendationer ännu',
    ],

    'tabs' => [
        'all' => 'Alla',
        'pending' => 'Väntande',
        'approved' => 'Godkända',
    ],

    'actions' => [
        'approve' => 'Godkänn',
        'reject' => 'Avvisa',
        'mark_as_spam' => 'Markera som skräppost',
        'approved_message' => 'Recensionen har godkänts.',
        'rejected_message' => 'Recensionen har avvisats.',
        'spam_message' => 'Recensionen har markerats som skräppost.',
        'spam_confirmation' => 'Att markera denna recension som skräppost döljer den för kunder. Detta kan ångras från moderationskön.',
    ],

];
