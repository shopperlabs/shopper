<?php

declare(strict_types=1);

return [

    'actions' => [
        'create' => ':item successfully added',
        'update' => ':item successfully updated',
        'remove' => ':item successfully removed'
    ],

    'attributes' => [
        'remove' => 'The attribute has successfully removed',
        'enable' => 'The attribute has successfully enabled',
        'disable' => 'The attribute has successfully disabled',
    ],

    'auth' => [
        'password' => 'This password does not match our records.'
    ],

    'analytics' => 'Your analytics configurations have been correctly updated',

    'store_info' => 'Shop informations have been successfully updated',

    'inventory' => [
        'removed' => 'Inventory Successfully removed.',
    ],

    'initialize' => 'Store successfully setup, you can now manage everything.',

    'legal' => 'Your legal policy has been successfully updated',

    'users_roles' => [
        'role_added' => 'A role has been successfully created',
        'role_deleted' => 'Role deleted successfully.',
        'admin_deleted' => 'Admin deleted successfully',
        'permission_add' => 'A new permission has been create and add to this role',
        'permission_revoke' => 'Permission :permission has been revoked to this role',
        'permission_allow' => 'Permission :permission has been given to this role',
        'password_changed' => 'You have been successfully updated your password',
        'current_password' => 'That is not your current password.',
        'profile_update' => 'Your profile have been successfully updated',
        'two_factor_enabled' => 'You have successfully enabled two-factor authentication',
        'two_factor_disabled' => 'You have successfully disabled two-factor authentication',
        'two_factor_generate' => 'You have successfully regenerated your two-factor authentication recovery codes.',
    ],

    'orders' => [
        'archived' => 'Order successfully archived',
    ],

    'payment' => [
        'add' => 'Your payment method have been correctly added!',
        'update' => 'Your payment method have been correctly updated',
    ],

    'products' => [
        'remove' => 'The :item has been correctly removed.',
    ],

];
