<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Product;

uses(Tests\Core\TestCase::class);

it('removes duplicate discountables before adding the unique index', function (): void {
    $table = shopper_table('discountables');

    Schema::table($table, function (Blueprint $blueprint): void {
        $blueprint->dropUnique('discountables_discount_target_unique');
    });

    $discount = Discount::factory()->create();
    $product = Product::factory()->create();

    $row = [
        'discount_id' => $discount->id,
        'discountable_type' => $product->getMorphClass(),
        'discountable_id' => $product->id,
        'total_use' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    DB::table($table)->insert([$row, $row, $row]);

    $migration = require dirname(__DIR__, 3).'/packages/core/database/migrations/2026_05_06_000002_add_unique_index_to_discountables_table.php';
    $migration->up();

    expect(DB::table($table)->where('discount_id', $discount->id)->count())->toBe(1)
        ->and(fn () => DB::table($table)->insert($row))->toThrow(QueryException::class);
})->group('discount', 'database');
