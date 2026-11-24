<?php

declare(strict_types=1);

return [

    'tagline' => 'Online Shopping tool',

    'customer' => [
        'subject' => 'Welcome to :name',
        'greeting' => 'Hello :name',
        'account_created' => 'An account has been created for you on the website :website',
        'set_password' => 'Choose a password to sign in to your account.',
        'set_password_action' => 'Choose my password',
        'link_expiry' => 'This link expires in :count minutes. After that, request a new one from the forgot password page.',
    ],

    'admin' => [
        'subject' => 'Welcome to Shopper',
        'greeting' => 'Hello :name',
        'account_created' => 'An account has been created for you as Administrator on the website :website',
        'set_password' => 'Choose a password to sign in to the administration.',
        'set_password_action' => 'Choose my password',
        'link_expiry' => 'This link expires in :count minutes. After that, request a new one from the forgot password page.',
    ],

];
