<?php

declare(strict_types=1);

return [

    'actions' => [
        'create' => ':item ajouté avec succès',
        'update' => ':item mis à jour avec succès',
        'remove' => ':item supprimé(e) avec succès',
    ],

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
    ],

    'initialize' => 'Le magasin ayant été configuré avec succès, vous pouvez maintenant tout gérer',

    'legal' => 'Votre politique juridique a été mise à jour avec succès',

    'users_roles' => [
        'role_deleted' => 'Rôle supprimé avec succès',
        'role_added' => 'Un nouveau rôle a été ajouté avec succès',
        'admin_deleted' => 'Admin supprimé avec succès',
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

    'orders' => [
        'archived' => 'Commande archivée avec succès',
    ],

    'payment' => [
        'add' => 'Votre méthode de paiement a été correctement ajoutée',
        'update' => 'Votre mode de paiement a été correctement mis à jour',
    ],

    'products' => [
        'remove' => ':item a été correctement supprimé',
    ],

];
