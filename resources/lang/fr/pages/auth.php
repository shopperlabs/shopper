<?php

return [

    'login' => [
        'title' => 'Content de te revoir !',
        'or' => 'Ou alors',
        'return_landing' => 'Retour à la page d\'accueil',
        'forgot_password' => 'Mot de passe oublié?',
        'action' => 'Connexion',
    ],

    'reset' => [
        'title' => 'Réinitialiser le mot de passe',
        'message' => 'Saisissez votre e-mail et le nouveau mot de passe que vous souhaitez utiliser pour accéder à votre compte.',
        'action' => 'Mettre à jour le mot de passe',
    ],

    'email' => [
        'title' => 'Réinitialisation du mot de passe',
        'message' => 'Entrez l\'adresse e-mail que vous avez utilisée lors de la création de votre compte et nous vous enverrons des instructions pour réinitialiser votre mot de passe.',
        'action' => 'Envoyer un e-mail de réinitialisation du mot de passe',
        'return_to_login' => 'Retour à la page de connexion',
        'mail' => [
            'content' => 'Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte.',
            'action' => 'Réinitialiser le mot de passe',
            'message' => 'Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune autre action n\'est requise.'
        ],
    ],

    'two_factor' => [
        'title' => 'Connectez-vous avec Two-Factor',
        'subtitle' => 'Authentifiez votre compte',
        'authentication_code' => 'Veuillez confirmer l\'accès à votre compte en saisissant le code d\'authentification fourni par votre application d\'authentification.',
        'recovery_code' => 'Veuillez confirmer l\'accès à votre compte en saisissant l\'un de vos codes de récupération d\'urgence.',
        'remember' => 'Vous ne vous souvenez plus de ce code?',
        'use_recovery_code' => 'Utiliser un code de récupération',
        'use_authentication_code' => 'Utiliser un code d\'authentification',
        'action' => 'Connexion',
    ],

    'account' => [
        'meta_title' => 'Compte de profil',
        'title' => 'Mon profil',

        'device_title' => 'Appareils',
        'device_description' => 'Vous êtes actuellement connecté sur ces appareils. Si vous ne reconnaissez pas un appareil, déconnectez-vous pour protéger votre compte.',
        'empty_device' => 'Si nécessaire, vous pouvez vous déconnecter de toutes vos autres sessions de navigation sur tous vos appareils.',
        'current_device' => 'Cet appareil',
        'device_last_activity' => 'Dernier actif',
        'device_location' => 'Impossible de récupérer cet emplacement.',
        'device_enabled_feature' => 'Le driver de session de base de données est nécessaire pour activer cette fonctionnalité.',

        'password_title' => 'Mettre à jour le mot de passe',
        'password_description' => 'Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester en sécurité.',
        'password_helper_validation' => 'Votre mot de passe doit comporter plus de 8 caractères et doit contenir au moins 1 majuscule, 1 minuscule et 1 chiffre.',

        'two_factor_title' => 'Authentification à deux facteurs',
        'two_factor_description' => 'Après avoir entré votre mot de passe, vérifiez votre identité avec une deuxième méthode d\'authentification.',
        'two_factor_enabled' => 'Vous avez activé l\'authentification à deux facteurs.',
        'two_factor_disabled' => 'Vous n\'avez pas activé l\'authentification à deux facteurs.',
        'two_factor_install_message' => 'Pour utiliser l\'authentification à deux facteurs, vous devez installer l\'application Google Authenticator sur votre smartphone.',
        'two_factor_secure' => 'Avec l\'authentification à deux facteurs, vous seul pouvez accéder à votre compte, même si quelqu\'un d\'autre connaît votre mot de passe.',
        'two_factor_activation_message' => 'Lorsque l\'authentification à deux facteurs est activée, vous serez invité à saisir un jeton aléatoire sécurisé lors de l\'authentification. Vous pouvez récupérer ce jeton à partir de l\'application Google Authenticator de votre téléphone.',
        'two_factor_is_enabled' => 'L\'authentification à deux facteurs est maintenant activée. Scannez le code QR suivant à l\'aide de l\'application d\'authentification de votre téléphone.',
        'two_factor_store_recovery_codes' => 'Stockez ces codes de récupération dans un gestionnaire de mots de passe sécurisé. Ils peuvent être utilisés pour récupérer l\'accès à votre compte si votre dispositif d\'authentification à deux facteurs est perdu.',

        'profile_title' => 'Informations sur le profil',
        'profile_description' => 'Mettez à jour les informations de profil et l\'adresse e-mail de votre compte.',
    ],
];
