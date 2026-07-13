<?php

declare(strict_types=1);

return [

    'menu' => 'Erbjudanden',
    'single' => 'rabatt',
    'title' => 'Hantera rabatter och erbjudanden',
    'description' => 'Skapa & hantera rabatt- och kampanjkoder som tillämpas i kassan eller på kundbeställningar.',

    'empty_message' => 'Ingen rabatt hittades...',
    'search' => 'Sök rabattkod',
    'name_helptext' => 'Kunder anger denna rabattkod i kassan.',

    'method' => 'Metod',
    'method_code' => 'Rabattkod',
    'method_code_description' => 'Kunder fyller i en kod i kassan för att lösa in denna rabatt.',
    'method_automatic' => 'Automatisk',
    'method_automatic_description' => 'Tillämpas automatiskt när varukorgen matchar villkoren, utan kod.',

    'type_percentage_description' => 'En procentuell rabatt på beställningen eller utvalda produkter.',
    'type_fixed_description' => 'Ett fast belopp i rabatt på beställningen eller utvalda produkter.',
    'apply_to_order_description' => 'Ger rabatt på hela orderbeloppet.',
    'apply_to_products_description' => 'Ger endast rabatt på utvalda produkter.',
    'eligibility_everyone_description' => 'Alla kan använda detta kampanjerbjudande.',
    'eligibility_customers_description' => 'Endast utvalda kunder kan använda det.',

    'exclusivity_class' => 'Exklusivitetsklass',
    'exclusivity_class_helptext' => 'Rabatter i samma klass kan aldrig kombineras med varandra.',
    'exclusivity_order' => 'Order',
    'exclusivity_product' => 'Produkt',
    'exclusivity_shipping' => 'Frakt',
    'combinable' => 'Kan kombineras med andra rabatter',
    'combinable_helptext' => 'Tillåt att denna rabatt kombineras med rabatter från andra exklusivitetsklasser.',
    'priority' => 'Prioritet',
    'priority_helptext' => 'Lägre nummer utvärderas först när flera rabatter konkurrerar.',

    'campaign' => 'Kampanj',
    'campaign_description' => 'Koppla denna rabatt till en kampanj för att dela dess budget och användningsgränser.',
    'campaign_helptext' => 'Rabatter i en kampanj delar samma budget och inlösenbegränsningar.',
    'campaign_locked_helper' => 'Kampanjen kan inte ändras efter att erbjudandet har använts, så att uppföljning av kampanjbudgeten förblir konsekvent.',
    'campaign_none' => 'Ingen kampanj',

    'wizard' => [
        'type' => 'Typ',
        'details' => 'Detaljer',
        'campaign' => 'Kampanj',
    ],

    'percentage' => 'Procent',
    'percentage_description' => 'Rabatt tillämpad i %',
    'fixed_amount' => 'Fast belopp',
    'fixed_amount_description' => 'Rabatt i hela tal',
    'configuration_description' => 'Rabattkoden gäller från det ögonblick publiceringsknappen trycks in, och förblir aktiv om den inte ändras.',
    'condition_description' => 'Rabattkoden gäller för alla produkter om inget annat anges.',
    'applies_to' => 'Gäller för',
    'entire_order' => 'Hela ordern',
    'specific_products' => 'Specifika produkter',
    'select_products' => 'Välj produkter',
    'min_requirement' => 'Minsta krav',
    'none' => 'Inga',
    'min_amount' => 'Minsta köpbelopp (:currency)',
    'min_value' => 'Minsta nödvändiga värde',
    'applies_only_selected' => 'Gäller endast för utvalda produkter.',
    'min_quantity' => 'Minsta antal artiklar',
    'customer_eligibility' => 'Kundbehörighet',
    'everyone' => 'Alla',
    'specific_customers' => 'Specifika kunder',
    'select_customers' => 'Välj kunder',
    'usage_limits' => 'Användningsgränser',
    'usage_label' => 'Begränsa totalt antal gånger denna rabatt kan användas',
    'usage_label_description' => 'Denna gräns gäller alla kunder gemensamt, inte individuellt.',
    'usage_value' => 'Värde för användningsgräns',
    'limit_one_per_user' => 'Begränsa till en användning per kund',
    'active_dates' => 'Aktiva datum',
    'active_dates_description' => 'Datum då rabatten kommer att vara tillgänglig för användare.',
    'start_date' => 'Startdatum',
    'choose_start_date' => 'Välj startdatum',
    'end_date' => 'Slutdatum',
    'choose_end_date' => 'Välj slutdatum',
    'empty_code' => 'Ingen information angiven ännu.',
    'count_items' => ':count artiklar',
    'min_purchase' => 'Minsta köp av',

    'modals' => [
        'stock_available' => ':stock tillgängliga',
        'add_products' => 'Lägg till produkter',
        'add_selected_products' => 'Lägg till valda produkter',
        'search_product' => 'Sök produkt efter namn',

        'add_customers' => 'Lägg till kunder',
        'search_customer' => 'Sök kund efter namn',
        'add_selected_customers' => 'Lägg till valda kunder',

        'remove' => [
            'title' => 'Radera denna kod',
            'description' => 'Är du säker på att du vill radera denna kod? All data kommer att tas bort. Denna åtgärd kan inte ångras.',
            'success_message' => 'Rabattkoden har tagits bort!',
        ],
    ],

    'active_today' => 'Aktiv idag',
    'active_from_today' => 'Aktiv från idag',
    'active_from' => 'Aktiv från :date',
    'active_date' => 'Aktiv :date',
    'active_from_to' => 'Aktiv från :start till :end',
    'one_per_customer' => 'en per kund',

    'save' => 'Rabattkoden :code har sparats!',
    'total_use' => 'Inlösen',

    'create' => [
        'description' => 'Konfigurera en ny rabattkod. Sammanfattningen till höger uppdateras när du fyller i formuläret så du ser exakt vad kunderna får.',
    ],

    'edit' => [
        'description' => 'Uppdatera rabatten och granska dess faktiska användning och intäktseffekt.',
    ],

    'sections' => [
        'general' => 'Allmänt',
        'general_description' => 'Kod, typ och synlighet för rabatten.',
        'configuration' => 'Konfiguration',
        'configuration_description' => 'Hur många gånger rabatten kan användas och när den är aktiv.',
        'targeting' => 'Målgrupp',
        'targeting_description' => 'Vilka produkter och kunder som är berättigade till denna rabatt.',
        'combinations' => 'Kombinationer',
        'combinations_description' => 'Styr hur denna rabatt kombineras med andra och dess prioritet vid utvärdering.',
        'advanced' => 'Avancerat',
        'advanced_description' => 'Anpassad metadata kopplad till rabatten.',
    ],

    'zone_frozen_helper' => 'Zonen kan inte ändras när en rabatt med ett fast belopp har använts. Valutakonsekvens bibehålls på befintliga beställningar.',

    'summary' => [
        'title' => 'Sammanfattning av regler',
        'empty' => 'Välj en typ och ett värde för att se sammanfattningen uppdateras i realtid.',
        'uses_total' => 'användningar max',
        'type_percentage' => ':value% rabatt',
        'type_fixed_amount' => ':amount rabatt',
        'minimum_price' => 'Varukorg ≥ :amount',
        'minimum_quantity' => 'Minst :count artikel|Minst :count artiklar',
        'visibility_public' => 'Offentlig',
        'visibility_hidden' => 'Dold',
        'rows' => [
            'type' => 'Typ',
            'code' => 'Kod',
            'zone' => 'Zon',
            'applies' => 'Gäller för',
            'for' => 'För',
            'minimum' => 'Minsta',
            'usage' => 'Användning',
            'usage_value' => '{1} :count användning max|[2,*] :count användningar max',
            'active' => 'Aktiv',
            'visibility' => 'Synlighet',
        ],
    ],

    'stats' => [
        'title' => 'Prestanda',
        'usage' => 'Användning',
        'orders' => 'Beställningar',
        'gross_revenue' => 'Bruttoomsättning',
        'discount_cost' => 'Rabattkostnad',
        'aov_with' => 'AOV med kod',
        'disclaimer' => 'Statistiken inkluderar betalda order sedan flytten av rabattspårning.',
    ],

    'actions' => [
        'duplicate' => 'Duplicera',
        'duplicate_confirm_heading' => 'Duplicera denna rabatt?',
        'duplicate_confirm_description' => 'En kopia kommer att skapas med en ny kod med suffixet `_COPY`, inaktiverad synlighet och en tom användningsräknare. Du kommer att omdirigeras till den nya rabatten för att slutföra redigeringen.',
        'duplicate_in_progress' => 'Duplicering pågår redan.',
        'duplicate_success' => 'Rabatten har duplicerats som :code.',
    ],

    'products_picker' => [
        'title' => 'Välj produkter som denna rabatt ska gälla för',
        'description' => 'Välj en eller flera produkter. De kommer att visas i rabattformuläret när du bekräftar valet.',
        'button' => 'Bläddra bland produkter',
        'bulk_add' => 'Lägg till valda produkter',
        'empty' => 'Inga matchande produkter hittades.',
        'empty_field' => 'Ingen produkt vald. Klicka på "Bläddra bland produkter" för att lägga till.',
        'required' => 'Välj minst en produkt när rabatten gäller specifika produkter.',
    ],

    'customers_picker' => [
        'title' => 'Välj kunder som är berättigade till denna rabatt',
        'description' => 'Välj en eller flera kunder. De kommer att visas i rabattformuläret när du bekräftar valet.',
        'button' => 'Bläddra bland kunder',
        'bulk_add' => 'Lägg till valda kunder',
        'empty' => 'Inga matchande kunder hittades.',
        'empty_field' => 'Ingen kund vald. Klicka på "Bläddra bland kunder" för att lägga till.',
        'required' => 'Välj minst en kund när rabatten riktar sig till specifika kunder.',
    ],

    'apply_to_switch' => [
        'heading' => 'Växla till hela beställningen?',
        'description' => 'Du har valt specifika produkter för denna rabatt. Om du byter till hela beställningen kommer valet av produkter att tas bort.',
        'submit' => 'Ja, byt och rensa',
        'cancel' => 'Spara specifika produkter',
    ],

    'eligibility_switch' => [
        'heading' => 'Växla till alla?',
        'description' => 'Du har valt specifika kunder. Om du byter till alla kommer valet av kunder att tas bort.',
        'submit' => 'Ja, byt och rensa',
        'cancel' => 'Behåll specifika kunder',
    ],

    'eligibility_picker' => [
        'required' => 'Välj minst ett mål för detta behörighetsläge.',
    ],
];
