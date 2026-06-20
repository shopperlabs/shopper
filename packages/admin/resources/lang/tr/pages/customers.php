<?php

declare(strict_types=1);

return [

    'menu' => 'Müşteriler',
    'single' => 'müşteri',
    'title' => 'Müşteri siparişlerini ve detaylarını yönet',
    'content' => 'Müşteri bilgilerinizi yönetebileceğiniz ve satın alma geçmişlerini görüntüleyebileceğiniz yerdir.',

    'overview' => 'Profil genel bakış',
    'overview_description' => 'Müşterinin posta alabileceği kalıcı bir adres kullanın.',
    'security_title' => 'Güvenlik',
    'security_description' => 'Bu kullanıcının hesabına giriş yapmak için kullanacağı rastgele bir şifre girin.',
    'address_title' => 'Adres',
    'address_description' => 'Bu müşterinin birincil adresi. Bu adres varsayılan teslimat adresi olarak tanımlanacaktır.',
    'notification_title' => 'Bildirimler',
    'notification_description' => 'Müşterilerinizi hesapları hakkında bilgilendirin.',
    'marketing_email' => 'Müşteri pazarlama e-postaları almayı kabul etti.',
    'marketing_description' => 'Varsa pazarlama e-postalarınıza abone olmadan önce müşterilerinizden izin almalısınız.',
    'send_credentials' => 'Müşteri kimlik bilgilerini gönder.',
    'credential_description' => 'Bu müşteriye bu bağlantı kimlik bilgileriyle birlikte bir e-posta gönderilecektir.',

    'period' => ':period süredir müşteri',

    'modal' => [
        'title' => 'Bu müşteriyi arşivle',
        'description' => 'Bu müşteriyi devre dışı bırakmak istediğinizden emin misiniz? Tüm verileri (siparişler ve adresler) mağazanızdan kalıcı olarak silinecektir. Bu işlem geri alınamaz.',
        'success_message' => 'Bu müşteriyi başarıyla arşivlediniz, artık müşteri listenizde görünmeyecek.',
    ],

    'profile' => [
        'title' => 'Profil',
        'description' => 'Müşterinizin tüm genel bilgileri burada bulunabilir.',
        'account' => 'Hesap',
        'account_description' => 'Bilgilerin müşteri hesabında nasıl kullanıldığını yönetin.',
        'marketing' => 'E-posta Pazarlama',
        'two_factor' => 'İki Faktörlü Kimlik Doğrulama',
    ],

    'addresses' => [
        'title' => 'Adresler',
        'shipping' => 'Teslimat Adresi',
        'billing' => 'Fatura Adresi',
        'default' => 'Varsayılan adres',
        'customer' => 'Müşteri adresleri',
        'empty_text' => 'Bu müşterinin henüz bir teslimat veya fatura adresi yok.',
    ],

    'orders' => [
        'placed' => 'Sipariş Tarihi',
        'total' => 'Toplam',
        'ship_to' => 'Teslimat Adresi',
        'order_number' => 'Sipariş :number',
        'details' => 'Sipariş Detayları',
        'items' => 'Sipariş ürünleri',
        'view' => 'Siparişi görüntüle',
        'empty_text' => 'Sipariş bulunamadı...',
        'no_shipping' => 'Kargo yöntemi yok',
        'estimated' => 'Kargo tarihi',
    ],

    'anonymize' => [
        'action' => 'Müşteriyi anonimleştir',
        'title' => 'Bu müşteriyi anonimleştir',
        'description' => 'Bu işlem, bu müşteri için tüm kişisel verileri (ad, e-posta, telefon, adresler) kalıcı olarak anonimleştirecektir. Sipariş geçmişi muhasebe amaçları için korunacaktır. Bu işlem geri alınamaz.',
        'confirm' => 'Evet, anonimleştir',
        'success' => 'Müşteri başarıyla anonimleştirildi.',
        'first_name' => 'Silindi',
        'last_name' => 'Müşteri',
    ],

];
