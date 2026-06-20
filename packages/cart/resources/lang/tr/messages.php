<?php

declare(strict_types=1);

return [

    'exceptions' => [
        'cart_completed' => 'Sepet zaten tamamlandı.',
        'cart_not_found' => 'Sepet bulunamadı.',
        'insufficient_stock' => 'Bu ürün için yetersiz stok.',
    ],

    'discount' => [
        'not_found' => 'İndirim kodu bulunamadı.',
        'not_active' => 'İndirim aktif değil.',
        'not_started' => 'İndirim henüz başlamadı.',
        'expired' => 'İndirimin süresi doldu.',
        'usage_limit_reached' => 'İndirim kullanım limitine ulaşıldı.',
        'already_used' => 'İndirim bu müşteri tarafından zaten kullanıldı.',
        'requires_login' => 'İndirim, giriş yapmış bir müşteri gerektirir.',
        'customer_not_eligible' => 'Müşteri bu indirim için uygun değil.',
        'not_available_in_zone' => 'İndirim bu bölgede mevcut değil.',
        'min_amount_not_reached' => 'Minimum satın alma tutarına ulaşılamadı.',
        'min_quantity_not_reached' => 'Minimum miktara ulaşılamadı.',
        'invalid_value' => 'İndirim değeri sıfırdan büyük olmalıdır.',
        'invalid_percentage' => 'Yüzdelik indirim %100\'ü geçemez.',
    ],

];
