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
    public static function groups(): array
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
     * @param  string  $item
     * @param  string|null  $group
     */
    public static function generate(string $item, string $group = null)
    {
        self::query()->firstOrCreate([
            'name' => 'browse_'. $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Browse :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to browse all the :item, with actions as search, filters and more.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'read_'. $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Read :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to read the content of a record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'edit_'. $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Edit :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to edit the content of a record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'add_'. $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Add :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to add a new record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'delete_'. $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Delete :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to removed a record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);
    }
}
