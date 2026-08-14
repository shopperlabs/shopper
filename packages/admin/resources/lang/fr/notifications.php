<?php

declare(strict_types=1);

return [

    'save' => ':item enregistré avec succès',
    'create' => ':item ajouté avec succès',
    'update' => ':item mis à jour avec succès',
    'delete' => ':item supprimé(e) avec succès',
    'enabled' => ':item activé(e) avec succès',
    'disabled' => ':item désactivé(e) avec succès',
    'verified' => ':item vérifié(e) avec succès',
    'approved' => ':item approuvé(e) avec succès',
    'disapproved' => ':item désapprouvé(e) avec succès',
    'visibility' => 'Visibilité pour :item a été modifiée avec succès',
    'saved' => 'Enregistré',

    'attributes' => [
        'remove' => 'L\'attribut a été supprimé avec succès',
        'enable' => 'L\'attribut a été activé avec succès',
        'disable' => 'L\'attribut a été désactivé avec succès',
    ],

    'auth' => [
        'password' => 'Ce mot de passe ne correspond pas à nos archives',
    ],

    'analytics' => 'Vos configurations analytiques ont été correctement mises à jour',

    'store_info' => 'Les informations sur le magasin ont été mises à jour avec succès',

    'inventory' => [
        'removed' => 'Inventaire supprimé avec succès',
        'updated' => 'Stock mis à jour avec succès.',
    ],

    'initialize' => 'Le magasin ayant été configuré avec succès, vous pouvez maintenant tout gérer',

    'legal' => 'Votre politique juridique a été mise à jour avec succès',

    'users_roles' => [
        'role_deleted' => 'Rôle supprimé avec succès',
        'role_added' => 'Un nouveau rôle a été ajouté avec succès',
        'admin_deleted' => 'Admin supprimé avec succès',
        'user_id_copied' => 'Identifiant copié dans le presse-papiers',
        'permission_add' => 'Une nouvelle autorisation a été créée et ajoutée à ce rôle',
        'permission_revoke' => 'La permission :permission a été révoquée pour ce rôle',
        'permission_allow' => 'La permission :permission a été donnée à ce rôle',
        'password_changed' => 'Vous avez mis à jour votre mot de passe avec succès',
        'current_password' => 'Ce n\'est pas votre mot de passe actuel',
        'profile_update' => 'Votre profil a été mis à jour avec succès',
        'two_factor_enabled' => 'Vous avez activé avec succès l\'authentification à deux facteurs',
        'two_factor_disabled' => 'Vous avez désactivé avec succès l\'authentification à deux facteurs',
        'two_factor_generate' => 'Vous avez régénéré avec succès vos codes de récupération de l\'authentification à deux facteurs',
    ],

    'passkeys' => [
        'registered' => 'Votre passkey a été enregistrée avec succès',
        'deleted' => 'Votre passkey a été supprimée avec succès',
        'failed' => 'Impossible d\'enregistrer votre passkey',
    ],

    'orders' => [
        'archived' => 'Commande archivée avec succès',
    ],

    'payment' => [
        'add' => 'Le moyen de paiement a été correctement enregistrée',
        'update' => 'Votre mode de paiement a été correctement mis à jour',
    ],

    'carrier' => [
        'add' => 'Le transporteur a été ajouté avec succès !',
        'update' => 'Le transporteur a été mis à jour avec succès.',
    ],

    'products' => [
        'remove' => ':item a été correctement supprimé',
    ],

    'shipments' => [
        'label_created' => 'Étiquette d\'expédition créée',
    ],

    'unauthorized' => [
        'title' => 'Non autorisé',
        'body' => 'Vous n\'avez pas la permission d\'effectuer cette action.',
        'administrator_role' => 'Seul un administrateur peut gérer le rôle administrateur.',
        'administrator_only' => 'Seul un administrateur peut créer ou supprimer une permission.',
        'permission_scope' => 'Vous ne pouvez accorder qu\'une permission que vous détenez vous-même.',
        'protected_permission' => 'Cette permission est nécessaire au panel et ne peut pas être supprimée.',
    ],

    'database' => [
        'view_products' => 'Voir les produits',
        'product_import' => [
            'title' => 'Import de produits terminé',
            'body' => ':imported produits importés, :failed en échec.',
        ],
        'open' => 'Ouvrir les notifications',
        'view_order' => 'Voir la commande',
        'order_created' => [
            'title' => 'Nouvelle commande reçue',
            'body' => 'La commande :number vient d\'être passée.',
        ],
        'order_paid' => [
            'title' => 'Commande payée',
            'body' => 'Le paiement de la commande :number a été reçu.',
        ],
        'payment_failed' => [
            'title' => 'Échec du paiement',
            'body' => 'Une tentative de paiement pour la commande :number a échoué.',
        ],
        'refund_failed' => [
            'title' => 'Échec du remboursement',
            'body' => 'Une tentative de remboursement pour la commande :number a échoué.',
        ],
    ],

];
