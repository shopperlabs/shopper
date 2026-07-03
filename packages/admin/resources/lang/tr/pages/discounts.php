<?php

declare(strict_types=1);

return [

    'menu' => 'İndirimler',
    'single' => 'indirim',
    'title' => 'İndirimleri ve promosyonları yönet',
    'description' => 'Ödeme sırasında veya müşteri siparişlerinde uygulanan indirim ve promosyon kodları oluşturun ve yönetin.',

    'empty_message' => 'İndirim bulunamadı...',
    'search' => 'İndirim kodu ara',
    'name_helptext' => 'Müşteriler ödeme sırasında bu indirim kodunu girecek.',
    'percentage' => 'Yüzde',
    'percentage_description' => '% olarak uygulanan indirim',
    'fixed_amount' => 'Sabit tutar',
    'fixed_amount_description' => 'Tam sayı olarak indirim',
    'configuration_description' => 'İndirim kodu, yayınla düğmesine bastığınız andan itibaren geçerli olur ve değiştirilmezse aktif kalır.',
    'condition_description' => 'İndirim kodu, değiştirilmediği takdirde tüm ürünler için geçerlidir.',
    'applies_to' => 'Uygulanacak Yer',
    'entire_order' => 'Tüm sipariş',
    'specific_products' => 'Belirli ürünler',
    'select_products' => 'Ürünleri seç',
    'min_requirement' => 'Minimum gereksinimler',
    'none' => 'Yok',
    'min_amount' => 'Minimum satın alma tutarı (:currency)',
    'min_value' => 'Minimum Gereken Değer',
    'applies_only_selected' => 'Yalnızca seçilen ürünlere uygulanır.',
    'min_quantity' => 'Minimum ürün miktarı',
    'customer_eligibility' => 'Müşteri uygunluğu',
    'everyone' => 'Herkes',
    'specific_customers' => 'Belirli müşteriler',
    'select_customers' => 'Müşterileri seç',
    'usage_limits' => 'Kullanım limitleri',
    'usage_label' => 'Bu indirimin toplamda kullanılabileceği sayıyı sınırla',
    'usage_label_description' => 'Bu limit tüm müşteriler için geçerlidir, bireysel değildir.',
    'usage_value' => 'Kullanım limit değeri',
    'limit_one_per_user' => 'Müşteri başına bir kullanımla sınırla',
    'active_dates' => 'Aktif tarihler',
    'active_dates_description' => 'İndirimin kullanıcılara sunulacağı tarihler.',
    'start_date' => 'Başlangıç tarihi',
    'choose_start_date' => 'Başlangıç tarihi seçin',
    'end_date' => 'Bitiş tarihi',
    'choose_end_date' => 'Bitiş tarihi seçin',
    'empty_code' => 'Henüz bilgi girilmedi.',
    'count_items' => ':count ürün',
    'min_purchase' => 'Minimum satın alma',

    'modals' => [
        'stock_available' => ':stock mevcut',
        'add_products' => 'Ürün Ekle',
        'add_selected_products' => 'Seçilen Ürünleri Ekle',
        'search_product' => 'Ürün adına göre ara',

        'add_customers' => 'Müşteri Ekle',
        'search_customer' => 'Müşteri adına göre ara',
        'add_selected_customers' => 'Seçilen Müşterileri Ekle',

        'remove' => [
            'title' => 'Bu kodu sil',
            'description' => 'Bu kodu silmek istediğinizden emin misiniz? Tüm veriler kaldırılacaktır. Bu işlem geri alınamaz.',
            'success_message' => 'İndirim kodu başarıyla kaldırıldı!',
        ],
    ],

    'active_today' => 'Bugün aktif',
    'active_from_today' => 'Bugünden itibaren aktif',
    'active_from' => ':date tarihinden itibaren aktif',
    'active_date' => ':date aktif',
    'active_from_to' => ':start - :end arası aktif',
    'one_per_customer' => 'müşteri başına bir',

    'save' => 'İndirim kodu :code başarıyla kaydedildi!',
    'total_use' => 'Kullanım Sayısı',

    'eligibility_picker' => [
        'required' => 'Bu uygunluk modu için en az bir hedef seçin.',
    ],
];
