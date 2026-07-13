<?php

declare(strict_types=1);

return [

    'menu' => 'Inställningar',
    'single' => 'inställning',
    'site' => 'Sajt',

    'empty_country_selector' => 'Välj ett land',
    'logo_description' => 'Butikens logotyp som kommer att visas på sajten. Denna logotyp kommer att visas på fakturor.',
    'confirm_password_content' => 'Av säkerhetsskäl, bekräfta lösenordet för att fortsätta.',

    'general' => [
        'title' => 'Butiksinställning',
        'store_details' => 'Butiksdetaljer',
        'store_detail_summary' => 'Kunder kommer att använda denna information för att kontakta dig.',
        'email_helper' => 'Kunder kommer att använda denna e-postadress om de behöver kontakta dig.',
        'phone_number_helper' => 'Kunder kommer att använda detta telefonnummer om de behöver ringa dig direkt.',
        'assets' => 'Bilder & logotyper',
        'assets_summary' => 'Butikens logotyp och omslagsbild som kommer att visas på sajten. Dessa bilder kommer att visas på fakturor.',
        'store_address' => 'Butiksadress',
        'store_address_summary' => 'Denna adress kommer att visas på dina fakturor. Du kan redigera adressen som används.',
        'store_currency' => 'Butikens valuta',
        'social_links' => 'Sociala länkar',
        'add_social_link' => 'Lägg till social länk',
        'social_links_summary' => 'Information om dina olika konton på sociala nätverk. Användare kommer att kunna kontakta dig direkt på dina officiella sidor.',
    ],

    'location' => [
        'menu' => 'Lagerplatser',
        'single' => 'lagerplats',
        'description' => 'Hantera platser där lager förvaras, beställningar hanteras och produkter säljs.',
        'count' => 'Du har :count konfigurerade lagerplats(er).',
        'add' => 'Lägg till lagerplats',
        'detail' => 'Detaljer',
        'detail_summary' => 'Ge denna lagerplats ett kort namn för att göra den lätt identifierbar. Du kommer att se detta namn i områden som produkter.',
        'address' => 'Lagerplatsens adress',
        'address_summary' => 'Fullständig information för din lagerplats. Ange giltig information då detta kan vara tillgängligt för kunder.',
        'set_default' => 'Sätt som standardlagerplats',
        'set_default_summary' => 'Lager på denna plats är tillgängligt för försäljning online och kommer att användas som standard',
        'priority_summary' => 'Lägre värden hanteras först vid allokering av lager över flera platser.',
        'is_default' => 'Detta är standardlagerplatsen. Om du vill ändra varifrån onlineordrar skickas, välj först en annan standardlagerplats.',
    ],

    'analytics' => [
        'google' => 'Google Analytics',
        'google_description' => 'Google Analytics spårar besökare på webbplatsen och genererar rapporter som hjälper dig med marknadsföring.',
        'gtag' => 'Google Tag Manager',
        'gtag_description' => 'Google Tag Manager gör det enkelt för marknadsansvariga att lägga till taggar (Analytics, remarketing, etc.)',
        'pixel' => 'Facebook Pixel',
        'pixel_description' => 'Facebook Pixel hjälper dig att skapa reklamkampanjer för att hitta nya kunder som liknar dina köpare.',
        'no_json' => 'Ingen JSON-fil tillagd',
    ],

    'legal' => [
        'title' => 'Rättslig policy',
        'refund' => 'Återbetalningspolicy',
        'privacy' => 'Integritetspolicy',
        'shipping' => 'Fraktpolicy',
        'terms_of_use' => 'Användarvillkor',
        'summary' => 'Definiera :policy som alla användare och konsumenter av produkterna i din butik lyder under.',
    ],
];
