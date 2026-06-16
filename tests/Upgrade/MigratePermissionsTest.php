<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

uses(Tests\Upgrade\TestCase::class);

function permissionsTable(): string
{
    return config('permission.table_names.permissions', 'permissions');
}

function seedLegacyPermission(string $name, string $group): void
{
    DB::table(permissionsTable())->insert([
        'name' => $name,
        'guard_name' => 'web',
        'group_name' => $group,
        'can_be_removed' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

beforeEach(function (): void {
    DB::table(permissionsTable())->delete();
});

describe('shopper:upgrade:permissions', function (): void {
    it('renames underscore permissions to dot notation and regroups them', function (): void {
        seedLegacyPermission('browse_brands', 'brands');
        seedLegacyPermission('add_products', 'products');
        seedLegacyPermission('edit_product_variants', 'products');
        seedLegacyPermission('access_setting', 'system');

        $this->artisan('shopper:upgrade:permissions', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(permissionsTable())->where('name', 'brands.browse')->value('group_name'))->toBe('catalog')
            ->and(DB::table(permissionsTable())->where('name', 'products.create')->exists())->toBeTrue()
            ->and(DB::table(permissionsTable())->where('name', 'products.variants.edit')->value('group_name'))->toBe('products')
            ->and(DB::table(permissionsTable())->where('name', 'system.settings')->value('group_name'))->toBe('system')
            ->and(DB::table(permissionsTable())->where('name', 'browse_brands')->exists())->toBeFalse();
    });

    it('does not modify data with `--dry-run`', function (): void {
        seedLegacyPermission('browse_orders', 'orders');

        $this->artisan('shopper:upgrade:permissions', ['--dry-run' => true])
            ->assertSuccessful();

        expect(DB::table(permissionsTable())->where('name', 'browse_orders')->exists())->toBeTrue()
            ->and(DB::table(permissionsTable())->where('name', 'orders.browse')->exists())->toBeFalse();
    });

    it('skips when no underscore permissions exist', function (): void {
        seedLegacyPermission('orders.browse', 'sales');

        $this->artisan('shopper:upgrade:permissions', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(permissionsTable())->where('name', 'orders.browse')->value('group_name'))->toBe('sales');
    });

    it('does not run twice without `--force`', function (): void {
        seedLegacyPermission('browse_brands', 'brands');

        $this->artisan('shopper:upgrade:permissions', ['--force' => true])->assertSuccessful();

        seedLegacyPermission('add_brands', 'brands');

        $this->artisan('shopper:upgrade:permissions')->assertSuccessful();

        expect(DB::table(permissionsTable())->where('name', 'add_brands')->exists())->toBeTrue()
            ->and(DB::table(permissionsTable())->where('name', 'brands.create')->exists())->toBeFalse();
    });

    it('skips a rename when the dot-notation name already exists', function (): void {
        seedLegacyPermission('browse_brands', 'brands');
        seedLegacyPermission('brands.browse', 'catalog');

        $this->artisan('shopper:upgrade:permissions', ['--force' => true])
            ->assertSuccessful();

        expect(DB::table(permissionsTable())->where('name', 'browse_brands')->exists())->toBeTrue()
            ->and(DB::table(permissionsTable())->where('name', 'brands.browse')->count())->toBe(1);
    });

    it('cancels when user refuses confirmation', function (): void {
        seedLegacyPermission('browse_brands', 'brands');

        $this->artisan('shopper:upgrade:permissions')
            ->expectsConfirmation('Rename these permissions to dot notation?', 'no')
            ->assertSuccessful();

        expect(DB::table(permissionsTable())->where('name', 'browse_brands')->exists())->toBeTrue()
            ->and(DB::table(permissionsTable())->where('name', 'brands.browse')->exists())->toBeFalse();
    });
})
    ->group('upgrade');
