<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @property-read int $id
 * @property string $name
 * @property string|null $display_name
 * @property bool $can_be_removed
 */
class Permission extends SpatiePermission
{
    protected $casts = [
        'can_be_removed' => 'boolean',
    ];

    public static function groups(): array
    {
        return [
            'system' => __('shopper::words.system'),
            'brands' => __('shopper::layout.sidebar.brands'),
            'categories' => __('shopper::layout.sidebar.categories'),
            'collections' => __('shopper::layout.sidebar.collections'),
            'products' => __('shopper::layout.sidebar.products'),
            'customers' => __('shopper::layout.sidebar.customers'),
            'orders' => __('shopper::layout.sidebar.orders'),
            'discounts' => __('shopper::layout.sidebar.discounts'),
        ];
    }

    public static function generate(string $item, ?string $group = null): void
    {
        self::query()->firstOrCreate([
            'name' => 'browse_' . $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Browse :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to browse all the :item, with actions as search, filters and more.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'read_' . $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Read :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to read the content of a record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'edit_' . $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Edit :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to edit the content of a record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'add_' . $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Add :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to add a new record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => 'delete_' . $item,
            'group_name' => $group ?? $item,
            'display_name' => __('Delete :item', ['item' => ucfirst($item)]),
            'description' => __('This permission allow you to removed a record of :item.', ['item' => $item]),
            'can_be_removed' => false,
        ]);
    }
}
