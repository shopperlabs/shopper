<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;

uses(Tests\Upgrade\TestCase::class);

beforeEach(function (): void {
    $this->publicPath = sys_get_temp_dir().'/shopper-upgrade-test-'.uniqid();
    mkdir($this->publicPath, 0755, true);

    $this->app->usePublicPath($this->publicPath);
    config()->set('filesystems.links', [$this->publicPath.'/storage' => storage_path('app/public')]);
});

afterEach(function (): void {
    (new Filesystem)->deleteDirectory($this->publicPath);
});

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

    it('leaves zero-decimal amounts untouched', function (): void {
        $product = Product::factory()->create();
        $xaf = DB::table(shopper_table('currencies'))->where('code', 'XAF')->value('id');

        Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $xaf,
            'amount' => 600,
        ]);

        $this->artisan('shopper:upgrade', ['--force' => true, '--path' => 'rector-target-that-does-not-exist'])
            ->assertSuccessful();

        expect(DB::table(shopper_table('prices'))->where('currency_id', $xaf)->value('amount'))->toBe(600);
    });

    it('runs shopper:link as part of the upgrade', function (): void {
        $this->artisan('shopper:upgrade', ['--force' => true, '--path' => 'rector-target-that-does-not-exist'])
            ->assertSuccessful();

        expect(is_link($this->publicPath.'/storage'))->toBeTrue();
    });

    it('never asks for a carrier package that is already installed', function (): void {
        config()->set('shopper.shipping.drivers.ups.enabled', true);

        $this->artisan('shopper:upgrade', ['--force' => true, '--path' => 'rector-target-that-does-not-exist'])
            ->doesntExpectOutputToContain('composer require shopper/ups')
            ->assertSuccessful();
    });
})
    ->group('upgrade');
