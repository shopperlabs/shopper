<?php

declare(strict_types=1);

return [

    'title' => 'Vergiler',
    'single' => 'Vergi Bölgesi',
    'description' => 'Mağazanız için vergi bölgelerini, oranlarını ve vergi davranışını yönetin.',
    'add_action' => 'Vergi bölgesi ekle',
    'empty_heading' => 'Vergi bölgesi yok',
    'empty_detail_heading' => 'Vergi bölgesi seçilmedi',
    'empty_detail_description' => 'Detaylarını ve oranlarını görüntülemek için bir vergi bölgesi seçin.',
    'inclusive' => 'Vergi dahil',
    'exclusive' => 'Vergi hariç',
    'inclusive_help' => 'KDV tarzı dahil fiyatlandırma için etkinleştirin (ör. Avrupa, Afrika).',
    'tax_behavior' => 'Vergi davranışı',
    'provider' => 'Vergi sağlayıcısı',
    'system_default' => 'Sistem (varsayılan)',
    'province_code' => 'İl / Eyalet kodu',
    'province_code_help' => 'ISO 3166-2 alt bölüm kodu (ör. US-CA, FR-IDF, GB-ENG).',
    'name_help' => 'Bu bölge için isteğe bağlı görünen ad (ör. Kaliforniya, Île-de-France).',

    'rates' => [
        'title' => 'Vergi Oranları',
        'add' => 'Oran Ekle',
        'add_heading' => ':name için vergi oranı',
        'update' => ':name güncelle',
        'rate' => 'Oran',
        'empty_heading' => 'Yapılandırılmış oran yok',
        'default_help' => 'Ürüne özel bir istisna uygulanmadığında bu oranı kullanın.',
        'combinable' => 'Birleştirilebilir',
        'combinable_help' => 'Bu oranın üst bölge oranlarıyla birikmesine izin verin.',
    ],

    'overrides' => [
        'add' => 'İstisna Oluştur',
        'add_heading' => ':name için istisna oranı',
        'update' => ':name istisnasını güncelle',
        'description' => 'Bir istisna, belirli ürünlere, ürün türlerine veya kategorilere farklı bir vergi oranı uygular.',
        'targets' => 'Hedefler',
        'targets_help' => 'Bu istisnanın hangi ürünlere, ürün türlerine veya kategorilere uygulanacağını seçin.',
        'target_type' => 'Hedef türü',
        'target_value' => 'Hedef değer',
        'add_target' => 'Hedef ekle',
        'product_types' => 'Ürün türleri',
        'products' => 'Ürünler',
        'categories' => 'Kategoriler',
    ],

];
