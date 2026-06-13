<?php

declare(strict_types=1);

return [

    'tagline' => 'Online Shopping tool',

    'customer' => [
        'subject' => 'Welcome to :name',
        'greeting' => 'Hello :name',
        'account_created' => 'An account has been created for you on the website :website',
        'credentials' => 'Email: :email - Password: :password',
        'can_login' => 'You can access to the website to login',
        'action' => 'Browse the website',
        'change_password' => 'After logging in you have to change your password.',
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
