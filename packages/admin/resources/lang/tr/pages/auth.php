<?php

declare(strict_types=1);

return [

    'login' => [
        'title' => 'E-posta ile giriş yap',
        'subtitle' => 'Shopper Yönetim Paneline Giriş Yapın',
        'or' => 'veya',
        'return_landing' => 'Ana sayfaya dön',
        'forgot_password' => 'Şifrenizi mi unuttunuz?',
        'action' => 'Giriş Yap',
        'failed' => 'Bu kimlik bilgileri kayıtlarımızla eşleşmiyor.',
        'throttled' => 'Çok fazla giriş denemesi. Lütfen :seconds saniye sonra tekrar deneyin.',
        'return_login' => 'Giriş sayfasına dön',
    ],

    'reset' => [
        'title' => 'Şifre sıfırla',
        'message' => 'Hesabınıza erişmek için kullanmak istediğiniz e-posta adresinizi ve yeni şifrenizi girin.',
        'action' => 'Şifreyi güncelle',
    ],

    'email' => [
        'title' => 'Şifrenizi sıfırlayın',
        'message' => 'E-postanızı aşağıya girin, size şifrenizi nasıl sıfırlayacağınıza dair talimatlar göndereceğiz.',
        'action' => 'Şifre sıfırlama e-postası gönder',
        'return_to_login' => 'Giriş sayfasına dön',
        'mail' => [
            'subject' => 'Şifre Sıfırlama',
            'content' => 'Bu e-postayı, hesabınız için bir şifre sıfırlama talebi aldığımız için alıyorsunuz.',
            'action' => 'Şifre sıfırla',
            'message' => 'Bir şifre sıfırlama talebinde bulunmadıysanız, herhangi bir işlem yapmanıza gerek yoktur.',
        ],
    ],

    'two_factor' => [
        'title' => 'İki Faktörlü ile Giriş',
        'subtitle' => 'Hesabınızı Doğrulayın',
        'authentication_code' => 'Kimlik doğrulama uygulamanız tarafından sağlanan doğrulama kodunu girerek hesabınıza erişimi onaylayın.',
        'recovery_code' => 'Acil durum kurtarma kodlarınızdan birini girerek hesabınıza erişimi onaylayın.',
        'remember' => 'Bu kodu hatırlamıyor musunuz?',
        'use_recovery_code' => 'Kurtarma kodu kullan',
        'use_authentication_code' => 'Doğrulama kodu kullan',
        'action' => 'Giriş Yap',
        'recovery_not_enabled' => 'Bu hesap için kurtarma kodları etkin değil.',
        'invalid_recovery_code' => 'Sağlanan iki faktörlü kurtarma kodu geçersiz.',
        'invalid_code' => 'Sağlanan iki faktörlü doğrulama kodu geçersiz.',
    ],

    'account' => [
        'meta_title' => 'Profil Hesabı',
        'title' => 'Profilim',

        'device_title' => 'Cihazlar',
        'device_description' => 'Şu anda bu cihazlarda oturum açmış durumdasınız. Bir cihazı tanımıyorsanız, hesabınızı güvende tutmak için çıkış yapın.',
        'empty_device' => 'Gerekirse, diğer tüm tarayıcı oturumlarınızdan çıkış yapabilirsiniz.',
        'current_device' => 'Bu cihaz',
        'device_last_activity' => 'Son aktif',
        'device_location' => 'Bu konum alınamıyor.',
        'device_enabled_feature' => 'Bu özelliği etkinleştirmek için veritabanı oturum sürücüsü gereklidir.',

        'password_title' => 'Şifre Güncelle',
        'password_description' => 'Hesabınızın güvende kalması için uzun, rastgele bir şifre kullandığından emin olun.',
        'password_helper_validation' => 'Şifreniz 8 karakterden uzun olmalı ve en az 1 büyük harf, 1 küçük harf ve 1 rakam içermelidir.',

        'two_factor_title' => 'İki Faktörlü Kimlik Doğrulama',
        'two_factor_description' => 'Şifrenizi girdikten sonra, ikinci bir doğrulama yöntemiyle kimliğinizi doğrulayın.',
        'two_factor_enabled' => 'İki faktörlü doğrulamayı etkinleştirdiniz.',
        'two_factor_disabled' => 'İki faktörlü doğrulamayı etkinleştirmediniz.',
        'two_factor_install_message' => 'İki faktörlü doğrulama kullanmak için akıllı telefonunuza Google Authenticator uygulamasını yüklemelisiniz.',
        'two_factor_secure' => 'İki faktörlü doğrulama ile, başka birinin şifreniz olsa bile hesabınıza yalnızca siz erişebilirsiniz.',
        'two_factor_activation_message' => 'İki faktörlü doğrulama etkinleştirildiğinde, kimlik doğrulama sırasında güvenli, rastgele bir token istenecektir. Bu token\'ı telefonunuzun Google Authenticator uygulamasından alabilirsiniz.',
        'two_factor_is_enabled' => 'İki faktörlü doğrulama artık etkin. Telefonunuzun kimlik doğrulama uygulamasını kullanarak aşağıdaki QR kodunu tarayın.',
        'two_factor_store_recovery_codes' => 'Bu kurtarma kodlarını güvenli bir şifre yöneticisinde saklayın. İki faktörlü doğrulama cihazınızı kaybederseniz hesabınıza erişimi kurtarmak için kullanılabilirler.',

        'profile_title' => 'Profil Bilgileri',
        'profile_description' => 'Hesabınızın profil bilgilerini ve e-posta adresini güncelleyin.',
    ],

];
