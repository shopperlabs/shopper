<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

uses(Tests\Upgrade\TestCase::class);

describe('shopper:upgrade', function (): void {
    it('chains the data migrations when forced', function (): void {
        $table = config('permission.table_names.permissions', 'permissions');

        DB::table($table)->delete();
        DB::table($table)->insert([
            'name' => 'browse_brands',
            'guard_name' => 'web',
            'group_name' => 'brands',
            'can_be_removed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('shopper:upgrade', ['--force' => true, '--path' => 'rector-target-that-does-not-exist'])
            ->assertSuccessful();

        expect(DB::table($table)->where('name', 'brands.browse')->value('group_name'))->toBe('catalog')
            ->and(DB::table($table)->where('name', 'browse_brands')->exists())->toBeFalse();
    });

    it('reconciles a drifted stock snapshot as part of the upgrade', function (): void {
        DB::table(shopper_table('inventory_histories'))->insert([
            'quantity' => 10,
            'old_quantity' => 0,
            'inventory_id' => DB::table(shopper_table('inventories'))->insertGetId([
                'name' => 'Main',
                'code' => 'main',
                'email' => 'main@shop.test',
                'city' => 'Douala',
                'street_address' => 'Akwa Avenue 34',
                'postal_code' => '00237',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'stockable_type' => 'product',
            'stockable_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('shopper:upgrade', ['--force' => true, '--path' => 'rector-target-that-does-not-exist'])
            ->assertSuccessful();

        expect(
            DB::table(shopper_table('stock_levels'))
                ->where('stockable_type', 'product')
                ->where('stockable_id', 1)
                ->value('quantity')
        )->toBe(10);
    });
})
    ->group('upgrade');
