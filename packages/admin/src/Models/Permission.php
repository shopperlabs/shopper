<?php

declare(strict_types=1);

namespace Shopper\Models;

use Spatie\Permission\Models\Permission as Model;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read ?string $display_name
 * @property-read bool $can_be_removed
 */
final class Permission extends Model
{
    /**
     * @return array<string, array<string, mixed>|string|null>
     */
    public static function groups(): array
    {
        return [
            'system' => __('shopper::words.system'),
            'catalog' => __('shopper::words.catalog'),
            'products' => __('shopper::pages/products.menu'),
            'sales' => __('shopper::words.sales'),
            'customers' => __('shopper::pages/customers.menu'),
            'inventory' => __('shopper::words.inventory'),
        ];
    }

    public static function generate(string $item, ?string $group = null): void
    {
        $group = $group ?? $item;

        self::query()->firstOrCreate([
            'name' => $item.'.browse',
            'group_name' => $group,
            'display_name' => __('shopper::permissions.generate.browse.display_name', ['item' => ucfirst($item)]),
            'description' => __('shopper::permissions.generate.browse.description', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => $item.'.read',
            'group_name' => $group,
            'display_name' => __('shopper::permissions.generate.read.display_name', ['item' => ucfirst($item)]),
            'description' => __('shopper::permissions.generate.read.description', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => $item.'.edit',
            'group_name' => $group,
            'display_name' => __('shopper::permissions.generate.edit.display_name', ['item' => ucfirst($item)]),
            'description' => __('shopper::permissions.generate.edit.description', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => $item.'.create',
            'group_name' => $group,
            'display_name' => __('shopper::permissions.generate.create.display_name', ['item' => ucfirst($item)]),
            'description' => __('shopper::permissions.generate.create.description', ['item' => $item]),
            'can_be_removed' => false,
        ]);

        self::query()->firstOrCreate([
            'name' => $item.'.delete',
            'group_name' => $group,
            'display_name' => __('shopper::permissions.generate.delete.display_name', ['item' => ucfirst($item)]),
            'description' => __('shopper::permissions.generate.delete.description', ['item' => $item]),
            'can_be_removed' => false,
        ]);
    }

    protected function casts(): array
    {
        return [
            'can_be_removed' => 'boolean',
        ];
    }
}
