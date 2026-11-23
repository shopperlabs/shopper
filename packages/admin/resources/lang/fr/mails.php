<?php

declare(strict_types=1);

return [

    'tagline' => 'Outil de vente en ligne',

    'customer' => [
        'subject' => 'Bienvenue sur :name',
        'greeting' => 'Bonjour :name',
        'account_created' => 'Un compte a été créé pour vous sur le site :website',
        'set_password' => 'Choisissez un mot de passe pour vous connecter à votre compte.',
        'set_password_action' => 'Choisir mon mot de passe',
        'link_expiry' => 'Ce lien expire dans :count minutes. Passé ce délai, demandez-en un nouveau depuis la page mot de passe oublié.',
    ],

    'admin' => [
        'subject' => 'Bienvenue sur Shopper',
        'greeting' => 'Bonjour :name',
        'account_created' => 'Un compte administrateur a été créé pour vous sur le site :website',
        'credentials' => 'E-mail : :email - Mot de passe : :password',
        'login_link' => 'Vous pouvez utiliser le lien suivant pour vous connecter :',
        'action' => 'Se connecter',
        'change_password' => 'Après votre connexion, vous devez changer votre mot de passe en cliquant sur votre nom en haut à droite de l\'administration',
    ],

];
