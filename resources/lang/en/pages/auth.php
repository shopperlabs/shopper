<?php

return [

    'login' => [
        'title' => 'Welcome Back !',
        'or' => 'Or',
        'return_landing' => 'Return to the landing page',
        'forgot_password' => 'Forgot your password?',
        'action' => 'Login',
    ],

    'reset' => [
        'title' => 'Reset password',
        'message' => 'Enter your email and the new password you\'d like to use to access your account.',
        'action' => 'Update password',
    ],

    'email' => [
        'title' => 'Reset your password',
        'message' => 'Enter the email address you used when creating your account and we will send you instructions to reset your password.',
        'action' => 'Send password reset mail',
        'return_to_login' => 'Return to login page',
        'mail' => [
            'content' => 'You are receiving this email because we received a password reset request for your account.',
            'action' => 'Reset password',
            'message' => 'If you did not request a password reset, no further action is required.'
        ],
    ],

    'two_factor' => [
        'title' => 'Login with Two-Factor',
        'subtitle' => 'Authenticate Your Account',
        'authentication_code' => 'Please confirm access to your account by entering the authentication code provided by your authenticator application.',
        'recovery_code' => 'Please confirm access to your account by entering one of your emergency recovery codes.',
        'remember' => 'Can\'t remember this code?',
        'use_recovery_code' => 'Use a recovery code',
        'use_authentication_code' => 'Use an authentication code',
        'action' => 'Login',
    ],

];
