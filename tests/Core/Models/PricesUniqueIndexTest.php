<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Product;

uses(Tests\Core\TestCase::class);

it('removes duplicate prices keeping the latest row before adding the unique index', function (): void {
    $table = shopper_table('prices');

    Schema::table($table, function (Blueprint $blueprint): void {
        $blueprint->dropUnique('prices_priceable_currency_unique');
        $blueprint->dropIndex('prices_priceable_currency_amount_index');
    });

    $product = Product::factory()->create();
    $currency = Currency::withoutGlobalScopes()->where('code', 'USD')->firstOrFail();

    $row = [
        'priceable_type' => $product->getMorphClass(),
        'priceable_id' => $product->id,
        'currency_id' => $currency->id,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    DB::table($table)->insert([
        [...$row, 'amount' => 1000],
        [...$row, 'amount' => 2000],
        [...$row, 'amount' => 3000],
    ]);

    $migration = require dirname(__DIR__, 3).'/packages/core/database/migrations/2026_07_31_000001_add_unique_currency_index_to_prices_table.php';
    $migration->up();

    $remaining = DB::table($table)->where('priceable_id', $product->id)->get();

    expect($remaining)->toHaveCount(1)
        ->and((int) $remaining->first()->amount)->toBe(3000)
        ->and(fn () => DB::table($table)->insert([...$row, 'amount' => 4000]))->toThrow(QueryException::class);
})->group('database');
