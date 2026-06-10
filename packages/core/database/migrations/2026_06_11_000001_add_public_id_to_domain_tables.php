<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array $tables = [
        'products',
        'product_variants',
        'product_tags',
        'attributes',
        'brands',
        'categories',
        'carriers',
        'carrier_options',
        'channels',
        'collections',
        'countries',
        'currencies',
        'discounts',
        'payment_methods',
        'prices',
        'inventories',
        'orders',
        'order_shipping',
        'suppliers',
        'zones',
    ];

    public function up(): void
    {
        foreach ($this->tables as $name) {
            $table = shopper_table($name);

            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'public_id')) {
                continue;
            }

            Schema::table($table, static function (Blueprint $blueprint): void {
                $blueprint->ulid('public_id')->nullable()->unique()->after('id');
            });

            DB::table($table)->whereNull('public_id')->orderBy('id')->lazyById()->each(
                fn (object $row) => DB::table($table)
                    ->where('id', $row->id)
                    ->update(['public_id' => (string) Str::ulid()])
            );
        }
    }
};
