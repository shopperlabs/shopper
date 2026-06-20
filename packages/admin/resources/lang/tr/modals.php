<?php

declare(strict_types=1);

return [

    'permissions' => [
        'new' => 'Yeni izin',
        'new_description' => 'Yeni bir izin ekleyin ve doğrudan bu role atayın',
        'labels' => [
            'name' => 'İzin adı (küçük harfle)',
        ],
    ],

    'roles' => [
        'new' => 'Yeni rol ekle',
        'new_description' => 'Yeni bir rol ekleyin ve yöneticiler için izinler atayın.',
        'labels' => [
            'name' => 'Ad (küçük harfle)',
        ],
        'confirm_delete_msg' => 'Bu rolü kaldırmak istediğinizden emin misiniz? Bu role sahip tüm kullanıcılar artık bu rolün verdiği eylemleri gerçekleştiremeyecek.',
    ],

    'attributes' => [
        'new_value' => ':attribute için yeni değer ekle',
        'key_description' => 'Anahtar, formlardaki (seçenek, radyo vb.) değerler için depolamada kullanılacaktır. Slug formatında olmalıdır.',
        'update_value' => ':name için değeri güncelle',
    ],

    'inventories' => [
        'confirm_delete_msg' => 'Bu envanteri silmek istediğinizden emin misiniz? Tüm veriler kaldırılacaktır. Bu işlem geri alınamaz.',
    ],

];
