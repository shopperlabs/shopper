<?php

declare(strict_types=1);

return [

    'tagline' => 'Online Shopping-verktyg',

    'customer' => [
        'subject' => 'Välkommen till :name',
        'greeting' => 'Hej :name',
        'account_created' => 'Ett konto har skapats på webbplatsen :website',
        'set_password' => 'Välj ett lösenord för att logga in på kontot.',
        'set_password_action' => 'Välj lösenord',
        'link_expiry' => 'Länken går ut om :count minuter. Därefter kan en ny länk begäras från sidan för glömt lösenord.',
    ],

    'admin' => [
        'subject' => 'Välkommen till Shopper',
        'greeting' => 'Hej :name',
        'account_created' => 'Ett administratörskonto har skapats på webbplatsen :website',
        'credentials' => 'E-post: :email - Lösenord: :password',
        'login_link' => 'Följande länk kan användas för att logga in:',
        'action' => 'Logga in',
        'change_password' => 'Efter inloggning bör lösenordet ändras genom att klicka på namnet i det övre högra hörnet av administrationsområdet',
    ],

];
