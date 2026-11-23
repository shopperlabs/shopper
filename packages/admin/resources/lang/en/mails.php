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
        'credentials' => 'Email: :email - Password: :password',
        'login_link' => 'You can use the following link to login:',
        'action' => 'Login',
        'change_password' => 'After logging in you need to change your password by clicking on your name in the upper right corner of the admin area',
    ],

];
