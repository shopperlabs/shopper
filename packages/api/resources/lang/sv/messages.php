<?php

declare(strict_types=1);

return [

    'cart' => [
        'empty' => 'Varukorgen är tom.',
        'nothing_to_collect' => 'Det finns inget belopp att samla in i varukorgen.',
        'no_zone' => 'Varukorgen har ingen fraktzon, så inget fraktalternativ är tillämpligt.',
        'email_required' => 'E-postadress måste anges innan varukorgen kan slutföras.',
        'metadata_too_large' => 'Metadata-belastningen är för stor.',
    ],

    'purchasable' => [
        'not_available' => 'Denna produkt är inte tillgänglig för köp.',
        'sold_through_variants' => 'Denna produkt säljs via sina varianter, lägg till en av dem istället.',
        'missing_price' => 'Denna produkt har inget pris för valutan :currency.',
    ],

    'shipping' => [
        'method_required' => 'Välj ett fraktsätt för varukorgen innan den slutförs.',
        'option_not_available' => 'Detta fraktalternativ är inte tillgängligt för varukorgen.',
        'option_gone' => 'Det valda fraktalternativet är inte längre tillgängligt.',
        'price_changed' => 'Fraktpriset har ändrats sedan det valdes. Välj fraktsätt igen för att bekräfta det nya priset.',
        'origin_missing' => 'Realtidspriser för frakt är inte tillgängliga: ingen lagerplats kan fungera som ursprung för leveransen.',
        'carrier_unavailable' => 'Priser från ":carrier" är tillfälligt otillgängliga.',
        'currency_mismatch' => 'Alternativ prissatta i :currency har tagits bort: varukorgen är prissatt i :cart_currency.',
    ],

    'payment' => [
        'method_required' => 'Välj ett betalsätt för varukorgen innan den slutförs.',
        'method_required_for_session' => 'Välj ett betalsätt för varukorgen innan en betalsession öppnas.',
        'method_not_available' => 'Detta betalsätt är inte tillgängligt för varukorgen.',
        'method_not_configured' => 'Betalsättet ":method" är inte konfigurerat.',
        'session_mismatch' => 'Betalsessionen matchar inte längre varukorgens totalbelopp. Skapa en ny betalsession och försök igen.',
    ],

    'order' => [
        'restricted_includes' => 'Endast `items` kan inkluderas vid sökning av orderbekräftelse. Använd konto-orderslutpunkten för hela ordern.',
    ],

    'promotion' => [
        'not_found' => 'Denna kampanjkod är ogiltig.',
        'not_applicable' => 'Denna kampanjkod kan inte tillämpas på varukorgen.',
    ],

];
