<?php

declare(strict_types=1);

return [
    'title' => 'Webhooks',
    'add_webhook' => 'Lägg till webhook',
    'empty' => 'Inga webhook-prenumerationer',
    'no_webhook' => 'Inga webhook-prenumerationer ännu. Lägg till en för att avisera ett externt system när händelser inträffar i butiken.',
    'events' => 'Händelser',
    'deliveries' => 'Leveranser',
    'no_delivery' => 'Inga leveranser ännu för denna webhook.',
    'attempt' => 'Försök :number',
    'redeliver' => 'Skicka igen',
    'redelivered' => 'Leveransen har köats på nytt.',
    'redeliver_refused' => 'Resursen för denna händelse finns inte längre, innehållet kan inte skickas igen.',
    'created' => 'Webhook har skapats',
    'updated' => 'Webhook har uppdaterats',
    'unsafe_url' => 'Webhook-URL:en måste använda https och peka på en offentlig adress.',
    'regenerate_secret' => 'Generera ny nyckel',
    'regenerate_secret_warning' => 'Den nuvarande nyckeln slutar fungera omedelbart. Uppdatera mottagande slutpunkt med den nya.',
    'secret_regenerated' => 'Nyckeln har genererats på nytt',
    'secret_reveal' => 'Signeringsnyckel (kopiera den nu, den visas inte igen): :secret',
    'cap_reached' => 'Maximalt antal webhook-prenumerationer har uppnåtts.',
];
