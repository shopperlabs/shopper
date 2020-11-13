<?php

namespace Shopper\Framework\Models\User;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'can_be_removed' => 'boolean',
    ];

    /**
     * Get a lists of permissions groups.
     *
     * @return array
     */
    public static function groups()
    {
        return [
          'system' => __("System"),
          'brands' => __("Brands"),
          'categories' => __("Categories"),
          'collections' => __("Collections"),
          'products' => __("Products"),
          'customers' => __("Customers"),
          'orders' => __("Orders"),
          'discounts' => __("Discounts"),
        ];
    }

    /**
     * Generate permissions for the group name.
     *
     * @param  string  $group_name
     */
    public static function generate(string $group_name)
    {
        self::query()->firstOrCreate([
            'name' => 'browse_'. $group_name,
            'group_name' => $group_name,
            'display_name' => __('Browse :permission', ['permission' => ucfirst($group_name)]),
            'description' => __("This permission allow you to browse all the :group, with actions as search, filters and more.", ['group' => $group_name]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'read_'. $group_name,
            'group_name' => $group_name,
            'display_name' => __('Read :permission', ['permission' => ucfirst($group_name)]),
            'description' => __("This permission allow you to read the content of a record of :group.", ['group' => $group_name]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'edit_'. $group_name,
            'group_name' => $group_name,
            'display_name' => __('Edit :permission', ['permission' => ucfirst($group_name)]),
            'description' => __("This permission allow you to edit the content of a record of :group", ['group' => $group_name]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'add_'. $group_name,
            'group_name' => $group_name,
            'display_name' => __('Add :permission', ['permission' => ucfirst($group_name)]),
            'description' => __("This permission allow you to add a new record of :group.", ['group' => $group_name]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'delete_'. $group_name,
            'group_name' => $group_name,
            'display_name' => __('Delete :permission', ['permission' => ucfirst($group_name)]),
            'description' => __("This permission allow you to removed a record of :group.", ['group' => $group_name]),
            'can_be_removed' => false,
        ]);
    }
}
