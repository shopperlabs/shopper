<?php

declare(strict_types=1);

return [

    'tagline' => 'Herramienta de compras en línea',

    'customer' => [
        'subject' => 'Bienvenido a :name',
        'greeting' => 'Hola :name',
        'account_created' => 'Se ha creado una cuenta para ti en el sitio web :website',
        'set_password' => 'Elige una contraseña para iniciar sesión en tu cuenta.',
        'set_password_action' => 'Elegir mi contraseña',
        'link_expiry' => 'Este enlace caduca en :count minutos. Después, solicita uno nuevo desde la página de contraseña olvidada.',
    ],

    'admin' => [
        'subject' => 'Bienvenido a Shopper',
        'greeting' => 'Hola :name',
        'account_created' => 'Se ha creado una cuenta de administrador para ti en el sitio web :website',
        'credentials' => 'Correo: :email - Contraseña: :password',
        'login_link' => 'Puedes usar el siguiente enlace para iniciar sesión:',
        'action' => 'Iniciar sesión',
        'change_password' => 'Después de iniciar sesión, debes cambiar tu contraseña haciendo clic en tu nombre en la esquina superior derecha del área de administración',
    ],

];
