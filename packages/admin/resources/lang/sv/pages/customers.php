<?php

declare(strict_types=1);

return [

    'menu' => 'Kunder',
    'single' => 'kund',
    'title' => 'Hantera kundbeställningar & uppgifter',
    'description' => 'Bläddra bland profiler, spåra livstidsaktivitet och hantera varje konto från en plats.',
    'content' => 'Här kan kundinformation hanteras och köphistorik visas.',

    'overview' => 'Profilöversikt',
    'overview_description' => 'Använd en permanent adress där kunden kan ta emot post.',
    'security_title' => 'Säkerhet',
    'security_description' => 'Ange ett slumpmässigt lösenord som användaren kommer att använda för att logga in på sitt konto.',
    'address_title' => 'Adress',
    'address_description' => 'Den primära adressen för kunden. Denna adress definieras som standardleveransadress.',
    'notification_title' => 'Aviseringar',
    'notification_description' => 'Informera kunderna om deras konto.',
    'marketing_email' => 'Kunden har godkänt att ta emot marknadsförings-e-post.',
    'marketing_description' => 'Användare bör tillfrågas om tillåtelse innan de registreras för marknadsförings-e-post.',
    'send_credentials' => 'Skicka inloggningsuppgifter till kunden.',
    'credential_description' => 'Ett e-postmeddelande kommer att skickas till denna kund med inloggningsuppgifterna.',

    'period' => 'Kund sedan :period',

    'stats' => [
        'total' => 'Totala kunder',
        'total_subtitle' => 'Alla registrerade konton',
        'new' => 'Nya kunder',
        'new_30_days' => 'under de senaste 30 dagarna',
        'new_empty' => 'Inga nya kunder under de senaste 30 dagarna',
        'active' => 'Aktiva kunder',
        'active_subtitle' => 'har lagt minst en betald order',
        'active_empty' => 'Inga aktiva kunder ännu',
        'avg_ltv' => 'Genomsnittligt livstidsvärde',
        'avg_ltv_subtitle' => 'Genomsnittlig intäkt per aktiv kund',
        'avg_ltv_empty' => 'Väntar på första betalda ordern',
    ],

    'header' => [
        'since' => 'Kund sedan :date',
        'orders_count' => '{0} inga beställningar|{1} :count beställning|[2,*] :count beställningar',
        'id' => 'Kund-ID #:id',
        'active' => 'Aktiv',
        'inactive' => 'Inaktiv',
        'previous' => 'Föregående kund',
        'next' => 'Nästa kund',
        'last_order' => 'Senaste ordern :time',
    ],

    'details' => [
        'title' => 'Kunduppgifter',
        'id' => 'Kund-ID',
        'copy_id' => 'Kopiera kund-ID',
        'copied' => 'Kopierat till urklipp',
        'created' => 'Skapad den',
        'email_status' => 'E-post',
        'email_verified' => 'Verifierad',
        'email_unverified' => 'Overifierad',
        'marketing_on' => 'Prenumererar',
        'marketing_off' => 'Avregistrerad',
        'two_factor_on' => 'Aktiverad',
        'two_factor_off' => 'Inaktiverad',
    ],

    'contact' => [
        'title' => 'Kontaktinformation',
        'no_phone' => 'Inget telefonnummer registrerat',
    ],

    'default_address' => [
        'title' => 'Standardadress',
        'empty' => 'Denna kund har ingen registrerad adress.',
    ],

    'create' => [
        'description' => 'Skapa ett kundkonto, ange inloggningsuppgifter och skicka valfritt ett välkomstmeddelande med inloggningsuppgifter via e-post.',
    ],

    'modal' => [
        'title' => 'Deaktivera kunden',
        'description' => 'Är du säker på att du vill inaktivera denna kund? All tillhörande data (beställningar & adresser) kommer att tas bort permanent från butiken. Denna åtgärd kan inte ångras.',
        'success_message' => 'Kunden har inaktiverats och finns inte längre i kundlistan.',
    ],

    'profile' => [
        'title' => 'Profil',
        'description' => 'All offentlig information om kunden finns här.',
        'account' => 'Konto',
        'account_description' => 'Hantera hur information används på kundkontot.',
        'marketing' => 'Marknadsförings-e-post',
        'two_factor' => 'Tvåfaktorsautentisering',
    ],

    'addresses' => [
        'title' => 'Adresser',
        'shipping' => 'Leveransadress',
        'billing' => 'Faktureringsadress',
        'shipping_section' => 'Leveransadresser',
        'billing_section' => 'Faktureringsadresser',
        'default' => 'Standard',
        'customer' => 'Kundadresser',
        'empty_text' => 'Denna kund har ännu inte registrerat någon leverans- eller faktureringsadress.',
        'shipping_empty_title' => 'Ingen leveransadress',
        'shipping_empty' => 'Denna kund har inte registrerat någon leveransadress ännu.',
        'billing_empty_title' => 'Ingen faktureringsadress',
        'billing_empty' => 'Denna kund har inte registrerat någon faktureringsadress ännu.',
    ],

    'orders' => [
        'placed' => 'Lagd order',
        'total' => 'Totalt',
        'ship_to' => 'Skicka till',
        'order_number' => 'Order :number',
        'details' => 'Orderdetaljer',
        'items' => 'Artiklar',
        'view' => 'Visa order',
        'empty_text' => 'Inga beställningar hittades...',
        'no_shipping' => 'Inget fraktsätt',
        'estimated' => 'Leveransdatum',
    ],

    'anonymize' => [
        'action' => 'Anonymisera kund',
        'title' => 'Anonymisera kunden',
        'description' => 'Denna åtgärd kommer att permanent anonymisera all personlig data för denna kund (namn, e-post, telefon, adresser). Orderhistorik kommer att sparas för bokföringsändamål. Denna åtgärd kan inte ångras.',
        'confirm' => 'Ja, anonymisera',
        'success' => 'Kunden har anonymiserats.',
        'first_name' => 'Borttagen',
        'last_name' => 'Kund',
    ],

    'picker' => [
        'title' => 'Välj kunder',
        'description' => 'Sök och välj en eller flera kunder.',
        'bulk_add' => 'Lägg till valda kunder',
        'empty' => 'Inga matchande kunder hittades.',
    ],
];
