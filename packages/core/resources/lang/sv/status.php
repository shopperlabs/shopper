<?php

declare(strict_types=1);

return [
    'archived' => 'Arkiverad',
    'awaiting' => 'Väntande',
    'cancelled' => 'Avbruten',
    'completed' => 'Slutförd',
    'new' => 'Ny',
    'not_paid' => 'Obetald',
    'not_refunded' => 'Ej återbetald',
    'partial-refund' => 'Delvis återbetald',
    'pending' => 'Väntande',
    'processing' => 'Bearbetar',
    'treatment' => 'Behandlas',
    'refunded' => 'Återbetald',
    'rejected' => 'Avvisad',

    'payment' => [
        'pending' => 'Väntande betalning',
        'authorized' => 'Auktoriserad',
        'paid' => 'Betald',
        'partially_refunded' => 'Delvis återbetald',
        'refunded' => 'Återbetald',
        'voided' => 'Makulerad',
    ],

    'shipping' => [
        'unfulfilled' => 'Ej levererad',
        'partially_shipped' => 'Delvis skickad',
        'shipped' => 'Skickad',
        'partially_delivered' => 'Delvis levererad',
        'delivered' => 'Levererad',
        'partially_returned' => 'Delvis returnerad',
        'returned' => 'Returnerad',
    ],

    'fulfillment' => [
        'pending' => 'Väntar på hantering',
        'forwarded' => 'Vidarebefordrad till leverantör',
        'processing' => 'Förbereder',
        'shipped' => 'Skickad',
        'delivered' => 'Levererad',
        'cancelled' => 'Avbruten',
    ],

    'shipment' => [
        'pending' => 'Sedel skapad',
        'picked_up' => 'Hämtad',
        'in_transit' => 'Under transport',
        'at_sorting_center' => 'På sorteringsterminal',
        'out_for_delivery' => 'Ute för leverans',
        'delivered' => 'Levererad',
        'delivery_failed' => 'Leverans misslyckades',
        'returned' => 'Returnerad',
    ],

    'stock' => [
        'reserved' => 'Reserverad för order',
        'cancelled' => 'Frisläppt från avbruten order',
    ],

    'discount' => [
        'draft' => 'Utkast',
        'scheduled' => 'Schemalagd',
        'active' => 'Aktiv',
        'disabled' => 'Inaktiverad',
        'expired' => 'Gått ut',
        'limit_reached' => 'Gräns uppnådd',
        'inapplicable' => 'Ej tillämplig',
    ],

    'campaign' => [
        'draft' => 'Utkast',
        'scheduled' => 'Schemalagd',
        'active' => 'Aktiv',
        'disabled' => 'Inaktiverad',
        'expired' => 'Gått ut',
        'budget_exhausted' => 'Budget slut',
    ],
];
