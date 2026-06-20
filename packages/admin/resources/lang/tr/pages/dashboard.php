<?php

declare(strict_types=1);

return [

    'menu' => 'Kontrol Paneli',
    'welcome_message' => 'Shopper\'a Hoş Geldiniz',
    'welcome_description' => 'Mağazanızı çalışır hale getirmek için ihtiyacınız olanlar.',

    'cards' => [
        'doc_title' => 'Dokümantasyon',
    ],

    'guide' => [
        'title' => 'Kurulum rehberi',
        'description' => 'Satışa başlamak için bu adımları tamamlayın.',
        'progress' => ':total üzerinden :count tamamlandı',
        'dismiss' => 'Kapat',
        'footer_hint' => 'Bu ayarlara daha sonra her zaman erişebilirsiniz.',

        'steps' => [
            'add_product' => [
                'title' => 'İlk ürününüzü ekleyin',
                'description' => 'Kataloğunuzu oluşturmaya başlamak için fiyat, görsel ve varyantlarla ürünler ekleyin.',
                'action' => 'Ürün ekle',
            ],
            'create_collection' => [
                'title' => 'Bir koleksiyon oluşturun',
                'description' => 'Müşterilerin mağazanızda gezinmesini kolaylaştırmak için ürünlerinizi koleksiyonlar halinde düzenleyin.',
                'action' => 'Koleksiyon oluştur',
            ],
            'setup_zones' => [
                'title' => 'Kargo bölgelerini ayarlayın',
                'description' => 'Teslimat yapacağınız yerleri ve maliyeti tanımlamak için kargo bölgelerinizi yapılandırın.',
                'action' => 'Kargoyu ayarla',
            ],
            'setup_payments' => [
                'title' => 'Ödeme yöntemlerini ayarlayın',
                'description' => 'Müşterilerinizin siparişlerini ödeyebilmesi için ödeme yöntemleri ekleyin.',
                'action' => 'Ödemeleri ayarla',
            ],
            'setup_taxes' => [
                'title' => 'Vergileri yapılandırın',
                'description' => 'Siparişlerde vergileri otomatik hesaplamak için vergi bölgeleri ve oranları belirleyin.',
                'action' => 'Vergileri yapılandır',
            ],
        ],
    ],

    'stats' => [
        'revenue' => 'Toplam Gelir',
        'products' => 'Toplam Ürün',
        'orders' => 'Toplam Sipariş',
        'customers' => 'Toplam Müşteri',
        'vs_last_month' => 'geçen aya göre',
        'view_more' => 'Daha fazla gör',
    ],

    'chart' => [
        'heading' => 'Performans',
        'series_label' => 'Gelir',
    ],

    'recent_orders' => [
        'heading' => 'Son Siparişler',
        'view_all' => 'Tümünü gör',
        'empty' => 'Henüz sipariş yok.',
    ],

    'top_products' => [
        'heading' => 'En Çok Satan Ürünler',
        'view_all' => 'Tümünü gör',
        'product' => 'Ürün',
        'sales' => 'Satışlar',
        'reviews' => 'Yorumlar',
        'empty' => 'Henüz satış yok.',
    ],

    'addons' => [
        'title' => 'Mağazanızı genişletin',
        'badge' => 'Eklenti',
        'learn_more' => 'Daha fazla bilgi',
        'configure' => 'Kargo şirketlerini yapılandır',

        'stripe' => [
            'title' => 'Stripe',
            'description' => 'Stripe ile kredi kartı, Apple Pay ve Google Pay kabul edin.',
        ],
        'carriers' => [
            'title' => 'Kargo şirketleri',
            'description' => 'Canlı kargo ücretleri için UPS, FedEx, USPS ve daha fazlasına bağlanın.',
        ],
    ],

];
