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

    'account' => [
        'meta_title' => 'Profile Account',
        'title' => 'My profile',

        'device_title' => 'Devices',
        'device_description' => 'You\'re currently logged in on these devices. If you don\'t recognize a device, log out to keep your account secure.',
        'empty_device' => 'If necessary, you may logout of all of your other browser sessions across all of your devices.',
        'current_device' => 'This device',
        'device_last_activity' => 'Last active',
        'device_location' => 'Unable to recover this location.',
        'device_enabled_feature' => 'Database session driver are needed to enable this feature.',

        'password_title' => 'Update Password',
        'password_description' => 'Ensure your account is using a long, random password to stay secure.',
        'password_helper_validation' => 'Your password must be more than 8 characters long and must contain at least 1 uppercase, 1 lowercase and 1 digit.',

        'two_factor_title' => 'Two Factor Authentication',
        'two_factor_description' => 'After entering your password, verify your identity with a second authentication method.',
        'two_factor_enabled' => 'You have enabled two factor authentication.',
        'two_factor_disabled' => 'You have not enabled two factor authentication.',
        'two_factor_install_message' => 'To utilize two factor authentication, you must install the Google Authenticator application on your smartphone.',
        'two_factor_secure' => 'With two factor authentication, only you can access your account — even if someone else has your password.',
        'two_factor_activation_message' => 'When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.',
        'two_factor_is_enabled' => 'Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application.',
        'two_factor_store_recovery_codes' => 'Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.',

        'profile_title' => 'Profile Information',
        'profile_description' => 'Update your account\'s profile information and email address.',
    ],
];
