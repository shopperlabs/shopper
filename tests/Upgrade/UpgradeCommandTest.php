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
})
    ->group('upgrade');
