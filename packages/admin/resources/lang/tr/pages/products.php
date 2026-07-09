<?php

declare(strict_types=1);

return [

    'menu' => 'Ürünler',
    'single' => 'ürün',
    'title' => 'Kataloğu Yönet',
    'content' => 'Ürün ekleyerek ve yöneterek ilk satışınıza yaklaşın.',
    'about_pricing' => 'Fiyat görüntüleme hakkında',
    'about_pricing_content' => 'Tüm fiyatlar varsayılan olarak sent cinsindendir. 10€ (veya 10$) kaydetmek için, para birimi biçimlendirmesinin doğru olması için 1000 sent girmelisiniz.',

    'amount_price_help_text' => 'İndirimler öncesi satın alma fiyatı.',
    'compare_price_help_text' => 'Satın alma fiyatıyla karşılaştırma için önerilen satış fiyatı. Bu fiyat genellikle daha yüksektir',
    'cost_per_items_help_text' => 'Orijinal üretim fiyatı. Müşteriler bunu görmez',
    'safety_security_help_text' => 'Güvenlik stoğu, ürün stoğunun yakında tükeneceği konusunda sizi uyaran limit stoktur.',
    'quantity_inventory' => 'Miktar Envanteri',
    'manage_inventories' => 'Envanterleri Yönet',
    'inventory_name' => 'Envanter adı',
    'product_can_returned' => 'Bu ürün iade edilebilir',
    'product_can_returned_help_text' => 'Bir sorun veya memnuniyetsizlik durumunda kullanıcılar bu ürünü iade etme seçeneğine sahiptir.',
    'product_shipped' => 'Bu ürün gönderilecek',
    'product_shipped_help_text' => 'Ürünün gönderimiyle ilgili bilgileri doldurduğunuzdan emin olun.',
    'general' => 'Ürün bilgisi',
    'status' => 'Ürün kullanılabilirliği',
    'featured_help_text' => 'Bu ürün öne çıkan olarak işaretlenecek.',
    'visible_help_text' => 'Bu ürün tüm satış kanallarından gizlenecek.',
    'availability_description' => 'Ürünlerinizin mağazanızda planlanması için bir yayın tarihi belirtin.',
    'type' => 'Ürün türü',
    'product_type' => 'Varsayılan ürün türü olarak ayarla',
    'product_type_helpText' => 'Bu yapılandırma, oluşturacağınız sonraki ürünler için kaydedilecektir.',
    'product_associations' => 'İlişkilendirmeler',
    'related_products' => 'İlgili Ürünler',
    'quantity_available' => 'Mevcut Miktar',
    'current_qty_inventory' => 'Bu envanterdeki mevcut miktar',
    'stock_inventory_heading' => 'Stok & Envanter',
    'stock_inventory_description' => 'Bu :item için envanter ve stoğu yapılandırın',
    'files_helpText' => 'Bu ürünün satın alınmasıyla birlikte indirilebilir olacak dosyaları ekleyin.',
    'images_helpText' => 'Ürününüze görseller ekleyin.',
    'variant_images_helpText' => 'Varyantınıza görseller ekleyin.',
    'use_as_thumbnail' => 'Küçük resim olarak kullan',
    'choose_from_images' => 'Görseller arasından seç',
    'use_as_thumbnail_description' => 'Küçük resim olarak kullanmak için ürün görsellerinden birini seçin.',
    'thumbnail_helpText' => 'Ödeme, sosyal paylaşım ve daha fazlası sırasında ürününüzü temsil etmek için kullanılır.',
    'weight_dimension' => 'Ağırlık ve Boyut',
    'weight_dimension_help_text' => 'Ödeme sırasında kargo ücretlerini hesaplamak ve sipariş işleme sırasında fiyatları etiketlemek için kullanılır.',
    'external_id_description' => 'Ürününüzün harici tedarikçiden gelen orijinal tanımlayıcısı',
    'allow_backorder' => 'Arka siparişe izin ver',

    'modals' => [
        'title' => 'Bu :item öğesini sil',
        'message' => 'Bu ürünü silmek istediğinizden emin misiniz? Bu ürünle ilişkili tüm bilgiler silinecektir.',

        'variants' => [
            'title' => 'Bu varyant için stok yönetimi',
            'select' => 'Envanter seç',
            'add' => 'Yeni varyant ekle',
            'options' => [
                'title' => 'Varyant özellikleri',
                'description' => 'Bu varyant için özellik seçeneklerini belirleyin.',
            ],
        ],
    ],

    'variants' => [
        'menu' => 'Varyantlar',
        'single' => 'varyant',
        'title' => 'Ürün varyasyonları',
        'description' => 'Ürününüzün tüm varyasyonları. Varyasyonların her biri kendi stok ve fiyatına sahip olabilir.',
        'add' => 'Varyant ekle',
        'generate' => 'Varyant oluştur',
        'generate_description' => 'Ürünleriniz seçtiğiniz özelliklere göre oluşturulur',
        'empty' => 'Varyant bulunamadı',
        'search_label' => 'Varyant ara',
        'search_placeholder' => 'Ürün varyantı ara',
        'variant_information' => 'Varyant bilgisi',
    ],

    'reviews' => [
        'single' => 'yorum',
        'title' => 'Müşteri yorumları',
        'description' => 'Müşterilerinizin yorumlarını ve ürünlerinize verilen puanları burada göreceksiniz.',
        'view' => ':product için yorumlar',
        'published' => 'Yayınlandı',
        'pending' => 'Beklemede',
        'approved' => 'Onaylanan Yorum',
        'is_recommended' => 'Önerilen Yorum',
        'approved_status' => 'Onay durumu',
        'approved_message' => 'Yorum onay durumu güncellendi!',

        'subtitle' => 'Bu ürün için yorum.',
        'reviewer' => 'Yorumcu',
        'review' => 'Yorum',
        'review_content' => 'İçerik',
        'status' => 'Durum',
        'rating' => 'Puan',
        'star' => 'yıldız',
        'stars' => 'yıldız',

        'modal' => [
            'title' => 'Yorumu Sil',
            'description' => 'Bu yorumu silmek istediğinizden emin misiniz? Bu yorum bir daha kurtarılamaz.',
            'success_message' => 'Yorum başarıyla kaldırıldı!',
        ],
    ],

    'attributes' => [
        'title' => 'Ürün Özellikleri',
        'description' => 'Bu ürünle ilişkili tüm özellikler.',
        'choose' => 'Özellik seç',
        'empty_title' => 'Etkin Özellik Yok',
        'empty_values' => 'Bu ürünle ilişkili özellikler burada listelenir.',

        'swatch' => [
            'action' => 'Değer görseli',
            'label' => 'Görsel',
            'help_text' => 'Bu değer için onaltılık renk kodu yerine gösterilen, bu ürüne özel isteğe bağlı görsel (örneğin ürünün bu renkteki veya malzemedeki bir fotoğrafı).',
            'updated' => 'Değer görseli güncellendi',
        ],

        'session' => [
            'delete' => 'Özellik kaldırıldı',
            'delete_message' => 'Bu özelliği üründen başarıyla kaldırdınız!',
            'delete_value' => 'Özellik değeri kaldırıldı',
            'delete_value_message' => 'Bu özelliğin değerini başarıyla kaldırdınız!',
            'added' => 'Özellik Eklendi',
            'added_message' => 'Bu ürüne özellikleri başarıyla eklediniz!',
        ],
    ],

    'inventory' => [
        'title' => 'Envanter özellikleri',
        'description' => 'Mağazanızdaki stok yönetimiyle ilgili alanlar.',
        'stock_title' => 'Stok yönetimi',
        'stock_description' => 'Farklı envanterlerinizde stok yönetimi.',
        'empty' => 'Envanterde düzenleme yapılmadı.',
        'movement' => 'Miktar Hareketi',
        'initial' => 'Başlangıç envanteri',
        'add' => 'Manuel eklendi',
        'remove' => 'Manuel kaldırıldı',
    ],

    'shipping' => [
        'description' => 'İade ürünü hakkında bilgi veya ürünün müşteriye gönderilip gönderilemeyeceğini tanımlayın.',
        'package_dimension' => 'Paket boyutu',
        'package_dimension_description' => 'Burada belirtilen paket boyutlarına göre ek kargo ücretleri uygulayın.',
    ],

    'related' => [
        'title' => 'Benzer Ürünler',
        'description' => 'Ürününüze benzer veya tamamlayıcı olarak tanımlanabilecek tüm ürünler.',
        'empty' => 'Benzer ürün bulunamadı',
        'add_content' => 'Ürününüze ilgili bir ürün ekleyerek başlayın.',

        'modal' => [
            'title' => 'Benzer Ürünler Ekle',
            'search' => 'Ürün ara',
            'search_placeholder' => 'Ürün adına göre ara',
            'action' => 'Seçilen Ürünleri Ekle',
            'success_message' => 'Seçilen ürün(ler) eklendi',
            'no_results' => 'Ürün bulunamadı',
        ],
    ],

    'notifications' => [
        'files_update' => 'Ürün dosyaları güncellendi!',
        'media_update' => 'Ürün medyası güncellendi!',
        'replicated' => 'Ürün kopyalandı!',
        'stock_update' => 'Ürün Stoğu başarıyla güncellendi!',
        'seo_update' => 'Ürün SEO\'su başarıyla güncellendi!',
        'shipping_update' => 'Ürün kargosu başarıyla güncellendi!',
        'variation_generate' => 'Ürün Varyantları başarıyla kaydedildi',
        'variation_create' => 'Ürün varyantı başarıyla eklendi!',
        'variation_delete' => 'Varyant başarıyla kaldırıldı!',
        'variation_update' => 'Varyant başarıyla güncellendi!',
        'related_added' => 'Ürün, ilgili ürünlere başarıyla eklendi!',
        'remove_related' => 'Ürün, ilgili ürünlerden başarıyla kaldırıldı!',
        'manage_pricing' => 'Ürün fiyatlandırmanız güncellendi!',
        'variant_already_exists' => 'Bu varyant zaten mevcut!',
    ],

    'pricing' => [
        'title' => 'Ürün fiyatlandırması',
        'description' => 'Ürününüzle ilişkili farklı fiyatlar. Bu, mağazanızdaki para birimlerine bağlıdır.',
        'add' => 'Yeni fiyat ekle',
        'empty' => 'Ürün fiyatlandırması eklenmedi',
    ],

    'picker' => [
        'title' => 'Ürün seç',
        'description' => 'Bir veya daha fazla ürün ara ve seç.',
        'bulk_add' => 'Seçili ürünleri ekle',
        'empty' => 'Eşleşen ürün bulunamadı.',
    ],
];
