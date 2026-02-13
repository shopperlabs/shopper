<?php

declare(strict_types=1);

return [

    'login' => [
        'title' => '¡Bienvenido de nuevo!',
        'or' => 'O',
        'return_landing' => 'Volver a la página de inicio',
        'forgot_password' => '¿Olvidaste tu contraseña?',
        'action' => 'Iniciar sesión',
        'failed' => 'Estas credenciales no coinciden con nuestros registros.',
        'throttled' => 'Demasiados intentos de inicio de sesión. Por favor inténtalo de nuevo en :seconds segundos.',
        'return_login' => 'Volver al inicio de sesión',
    ],

    'reset' => [
        'title' => 'Restablecer contraseña',
        'message' => 'Ingresa tu correo electrónico y la nueva contraseña que te gustaría usar para acceder a tu cuenta.',
        'action' => 'Actualizar contraseña',
    ],

    'email' => [
        'title' => 'Restablece tu contraseña',
        'message' => 'Ingresa la dirección de correo electrónico que usaste al crear tu cuenta y te enviaremos instrucciones para restablecer tu contraseña.',
        'action' => 'Enviar correo de restablecimiento de contraseña',
        'return_to_login' => 'Volver a la página de inicio de sesión',
        'mail' => [
            'content' => 'Estás recibiendo este correo porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.',
            'action' => 'Restablecer contraseña',
            'message' => 'Si no solicitaste un restablecimiento de contraseña, no se requiere ninguna otra acción.',
        ],
    ],

    'two_factor' => [
        'title' => 'Inicio de sesión con Dos Factores',
        'subtitle' => 'Autentica tu cuenta',
        'authentication_code' => 'Por favor confirma el acceso a tu cuenta ingresando el código de autenticación proporcionado por tu aplicación de autenticación.',
        'recovery_code' => 'Por favor confirma el acceso a tu cuenta ingresando uno de tus códigos de recuperación de emergencia.',
        'remember' => '¿No recuerdas este código?',
        'use_recovery_code' => 'Usar un código de recuperación',
        'use_authentication_code' => 'Usar un código de autenticación',
        'action' => 'Iniciar sesión',
    ],

    'account' => [
        'meta_title' => 'Perfil de Cuenta',
        'title' => 'Mi perfil',

        'device_title' => 'Dispositivos',
        'device_description' => 'Actualmente has iniciado sesión en estos dispositivos. Si no reconoces un dispositivo, cierra sesión para mantener tu cuenta segura.',
        'empty_device' => 'Si es necesario, puedes cerrar sesión en todas tus otras sesiones de navegador en todos tus dispositivos.',
        'current_device' => 'Este dispositivo',
        'device_last_activity' => 'Última actividad',
        'device_location' => 'No se puede recuperar esta ubicación.',
        'device_enabled_feature' => 'El driver de sesión de base de datos es necesario para habilitar esta función.',

        'password_title' => 'Actualizar contraseña',
        'password_description' => 'Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerse segura.',
        'password_helper_validation' => 'Tu contraseña debe tener más de 8 caracteres y debe contener al menos 1 mayúscula, 1 minúscula y 1 dígito.',

        'two_factor_title' => 'Autenticación de Dos Factores',
        'two_factor_description' => 'Después de ingresar tu contraseña, verifica tu identidad con un segundo método de autenticación.',
        'two_factor_enabled' => 'Has habilitado la autenticación de dos factores.',
        'two_factor_disabled' => 'No has habilitado la autenticación de dos factores.',
        'two_factor_install_message' => 'Para usar la autenticación de dos factores, debes instalar la aplicación Google Authenticator en tu smartphone.',
        'two_factor_secure' => 'Con la autenticación de dos factores, solo tú puedes acceder a tu cuenta — incluso si alguien más tiene tu contraseña.',
        'two_factor_activation_message' => 'Cuando la autenticación de dos factores está habilitada, se te pedirá un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.',
        'two_factor_is_enabled' => 'La autenticación de dos factores está ahora habilitada. Escanea el siguiente código QR usando la aplicación de autenticación de tu teléfono.',
        'two_factor_store_recovery_codes' => 'Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Se pueden usar para recuperar el acceso a tu cuenta si pierdes tu dispositivo de autenticación de dos factores.',

        'profile_title' => 'Información del Perfil',
        'profile_description' => 'Actualiza la información del perfil de tu cuenta y la dirección de correo electrónico.',
    ],

];
