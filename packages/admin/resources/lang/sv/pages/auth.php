<?php

declare(strict_types=1);

return [

    'login' => [
        'title' => 'Logga in med e-post',
        'subtitle' => 'Logga in på butikens administrationspanel',
        'or' => 'Eller',
        'return_landing' => 'Återvänd till startsidan',
        'forgot_password' => 'Glömt lösenordet?',
        'action' => 'Logga in',
        'failed' => 'Dessa uppgifter stämmer inte överens med våra register.',
        'throttled' => 'För många inloggningsförök. Försök igen om :seconds sekunder.',
        'return_login' => 'Tillbaka till inloggning',
        'passkey_action' => 'Logga in med en passkey',
    ],

    'session_expired' => [
        'title' => 'Återuppta sessionen',
        'description' => 'Sessionen har löpt ut. Ange lösenordet för att fortsätta där du slutade.',
        'submit' => 'Logga in',
        'submitting' => 'Loggar in…',
    ],

    'reset' => [
        'title' => 'Återställ lösenord',
        'message' => 'Ange din e-post och det nya lösenordet som ska användas för kontot.',
        'action' => 'Återställ lösenord',
    ],

    'email' => [
        'title' => 'Återställ lösenord',
        'message' => 'Ange din e-postadress nedan, så skickas instruktioner för att återställa lösenordet.',
        'action' => 'Skicka återställningslänk',
        'return_to_login' => 'Återvänd till inloggningssidan',
        'mail' => [
            'subject' => 'Återställ lösenord',
            'content' => 'Detta meddelande skickas eftersom en begäran om återställning av lösenord har mottagits för kontot.',
            'action' => 'Återställ lösenord',
            'message' => 'Om ingen återställning har begärts krävs ingen ytterligare åtgärd.',
        ],
    ],

    'two_factor' => [
        'title' => 'Logga in med tvåfaktor',
        'subtitle' => 'Autentisera kontot',
        'authentication_code' => 'Bekräfta åtkomst till kontot genom att ange autentiseringskoden från din autentiseringsapp.',
        'recovery_code' => 'Bekräfta åtkomst till kontot genom att ange en av dina nödräddningskoder.',
        'remember' => 'Kommer du inte ihåg koden?',
        'use_recovery_code' => 'Använd en återställningskod',
        'use_authentication_code' => 'Använd en autentiseringskod',
        'action' => 'Logga in',
        'recovery_not_enabled' => 'Återställningskoder är inte aktiverade för det här kontot.',
        'invalid_recovery_code' => 'Den angivna tvåfaktorsåterställningskoden var ogiltig.',
        'invalid_code' => 'Den angivna tvåfaktorsautentiseringskoden var ogiltig.',
    ],

    'account' => [
        'meta_title' => 'Profilkonto',
        'title' => 'Min profil',

        'device_title' => 'Enheter',
        'device_description' => 'Inloggning är för närvarande aktiv på dessa enheter. Logga ut från enheter som inte känns igen för att hålla kontot säkert.',
        'empty_device' => 'Vid behov kan utloggning ske från alla andra webbläsarsessioner på alla dina enheter.',
        'current_device' => 'Denna enhet',
        'device_last_activity' => 'Senast aktiv',
        'device_location' => 'Det gick inte att hämta platsen.',
        'device_enabled_feature' => 'Databassessionsdrivrutiner krävs för att aktivera denna funktion.',

        'password_title' => 'Uppdatera lösenord',
        'password_description' => 'Se till att kontot använder ett långt, slumpmässigt lösenord för att förbli säkert.',
        'password_helper_validation' => 'Lösenordet måste vara more än 8 tecken långt och innehålla minst 1 stor bokstav, 1 liten bokstav och 1 siffra.',

        'two_factor_title' => 'Two Factor Authentication',
        'two_factor_description' => 'EFter att du angett lösenordet verifieras identiteten med en andra autentiseringsmetod.',
        'two_factor_enabled' => 'Du har aktiverat tvåfaktorautentisering.',
        'two_factor_disabled' => 'Du har inte aktiverat tvåfaktorautentisering.',
        'two_factor_install_message' => 'För att använda tvåfaktorautentisering måste Google Authenticator installeras på din smartphone.',
        'two_factor_secure' => 'Med tvåfaktorautentisering kan endast du komma åt kontot — även om någon annan har lösenordet.',
        'two_factor_activation_message' => 'När tvåfaktorautentisering är aktiverat efterfrågas en säker, slumpmässig token vid inloggning. Denna token hämtas från appen Google Authenticator i din telefon.',
        'two_factor_is_enabled' => 'Tvåfaktorautentisering är nu aktiverat. Skanna följande QR-kod med din autentiseringsapp.',
        'two_factor_store_recovery_codes' => 'Spara dessa återställningskoder i en säker lösenordshanterare. De kan användas för att återfå åtkomst till kontot om tvåfaktorsenheten går förlorad.',

        'passkeys_title' => 'Passkeys',
        'passkeys_description' => 'Logga in lösenordsfritt med Face ID, Touch ID, Windows Hello eller en hårdvarunyckel.',
        'passkeys_count' => '{0} Inga passkeys registrerade|{1} :count passkey registrerad|[2,*] :count passkeys registrerade',
        'passkeys_secure' => 'Passkeys är nätfiskesäkra inloggningsuppgifter som lagras på din enhet. Endast du kan godkänna en inloggning med ditt fingeravtryck, ansikte eller enhetens PIN-kod.',
        'passkeys_unsupported' => 'Webbläsaren stöder inte passkeys.',
        'passkey_add' => 'Lägg till passkey',
        'passkey_add_description' => 'Namnge denna passkey så att du känner igen den senare, och följ sedan instruktionerna i webbläsaren.',
        'passkey_name_placeholder' => 't.ex. MacBook Pro',
        'passkey_added' => 'Tillagd den :date',
        'passkey_last_used' => 'Använd senast den :date',
        'passkey_never_used' => 'Aldrig använd',
        'passkey_delete' => 'Ta bort passkey',
        'passkey_delete_confirmation' => 'Denna passkey kommer inte längre att kunna användas för att logga in på kontot. Ange lösenordet för att bekräfta.',
        'passkey_password_confirmation_required' => 'Bekräfta lösenordet innan du hanterar passkeys.',

        'profile_title' => 'Profilinformation',
        'profile_description' => 'Uppdatera kontots profilinformation och e-postadress.',
    ],

];
