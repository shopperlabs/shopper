<?php

declare(strict_types=1);

return [

    'save' => ':item har sparats',
    'create' => ':item har skapats',
    'update' => ':item har uppdaterats',
    'delete' => ':item har raderats',
    'enabled' => ':item har aktiverats',
    'disabled' => ':item har inaktiverats',
    'verified' => ':item har verifierats',
    'approved' => ':item har godkänts',
    'disapproved' => ':item har underkänts',
    'visibility' => 'Synlighet för :item har uppdaterats',
    'saved' => 'Sparad',

    'attributes' => [
        'remove' => 'Attributet har tagits bort',
        'enable' => 'Attributet har aktiverats',
        'disable' => 'Attributet har inaktiverats',
    ],

    'auth' => [
        'password' => 'Detta lösenord matchar inte våra uppgifter.',
    ],

    'analytics' => 'Analyskonfigurationerna har uppdaterats',

    'store_info' => 'Butiksinformationen har uppdaterats',

    'inventory' => [
        'removed' => 'Lagerplatsen har tagits bort.',
        'updated' => 'Lagersaldot har uppdaterats.',
    ],

    'initialize' => 'Butiken har ställts in, allt kan nu hanteras.',

    'legal' => 'Rättslig policy har uppdaterats',

    'users_roles' => [
        'role_added' => 'Rollen har skapats',
        'role_deleted' => 'Rollen har raderats.',
        'admin_deleted' => 'Administratören har raderats',
        'user_id_copied' => 'Användar-ID kopierat till urklipp',
        'permission_add' => 'En ny behörighet har skapats och lagts till för denna roll',
        'permission_revoke' => 'Behörigheten :permission har återkallats för denna roll',
        'permission_allow' => 'Behörigheten :permission har beviljats för denna roll',
        'password_changed' => 'Lösenordet har uppdaterats',
        'current_password' => 'Det där är inte det nuvarande lösenordet.',
        'profile_update' => 'Profilen har uppdaterats',
        'two_factor_enabled' => 'Tvåfaktorautentisering har aktiverats',
        'two_factor_disabled' => 'Tvåfaktorautentisering har inaktiverats',
        'two_factor_generate' => 'Återställningskoderna för tvåfaktorautentisering har genererats på nytt.',
    ],

    'passkeys' => [
        'registered' => 'Passkey har registrerats',
        'deleted' => 'Passkey har raderats',
        'failed' => 'Det gick inte att registrera passkey',
    ],

    'orders' => [
        'archived' => 'Ordern har arkiverats',
    ],

    'payment' => [
        'add' => 'Betalsättet har sparats!',
        'update' => 'Betalsättet har uppdaterats',
    ],

    'carrier' => [
        'add' => 'Transportören har lagts till!',
        'update' => 'Transportören har uppdaterats.',
    ],

    'products' => [
        'remove' => ':item har tagits bort.',
    ],

    'shipments' => [
        'label_created' => 'Fraktsedel har skapats',
    ],

    'unauthorized' => [
        'title' => 'Obehörig',
        'body' => 'Behörighet saknas för att utföra denna åtgärd.',
        'administrator_role' => 'Endast en administratör kan hantera administratörsrollen.',
        'administrator_only' => 'Endast en administratör kan skapa eller ta bort en behörighet.',
        'permission_scope' => 'Du kan endast tilldela en behörighet som du själv har.',
        'protected_permission' => 'Denna behörighet krävs av panelen och kan inte tas bort.',
    ],

    'database' => [
        'view_products' => 'Visa produkter',
        'product_import' => [
            'title' => 'Produktimport slutförd',
            'body' => ':imported produkter importerade, :failed misslyckades.',
        ],
        'open' => 'Öppna aviseringar',
        'view_order' => 'Visa order',
        'order_created' => [
            'title' => 'Ny order mottagen',
            'body' => 'Order :number har precis lagts.',
        ],
        'order_paid' => [
            'title' => 'Order betald',
            'body' => 'Betalning för order :number har mottagits.',
        ],
        'payment_failed' => [
            'title' => 'Betalning misslyckades',
            'body' => 'Ett betalningsförsök för order :number har misslyckats.',
        ],
        'refund_failed' => [
            'title' => 'Återbetalning misslyckades',
            'body' => 'Ett återbetalningsförsök för order :number har misslyckats.',
        ],
    ],

];
