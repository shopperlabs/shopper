<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $lines = $this->getTableName('cart_lines');

        $duplicates = DB::table($lines)
            ->select('cart_id', 'purchasable_type', 'purchasable_id')
            ->selectRaw('MIN(id) as keep_id, SUM(quantity) as total_quantity')
            ->groupBy('cart_id', 'purchasable_type', 'purchasable_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::transaction(function () use ($lines, $duplicate): void {
                $removed = DB::table($lines)
                    ->where('cart_id', $duplicate->cart_id)
                    ->where('purchasable_type', $duplicate->purchasable_type)
                    ->where('purchasable_id', $duplicate->purchasable_id)
                    ->where('id', '<>', $duplicate->keep_id)
                    ->pluck('id');

                DB::table($this->getTableName('cart_line_adjustments'))->whereIn('cart_line_id', $removed)->delete();
                DB::table($this->getTableName('cart_line_tax_lines'))->whereIn('cart_line_id', $removed)->delete();
                DB::table($lines)->whereIn('id', $removed)->delete();

                DB::table($lines)->where('id', $duplicate->keep_id)->update(['quantity' => (int) $duplicate->total_quantity]);
                DB::table($this->getTableName('carts'))->where('id', $duplicate->cart_id)->update(['calculated_at' => null]);
            });
        }

        Schema::table($lines, static function (Blueprint $table): void {
            $table->unique(['cart_id', 'purchasable_type', 'purchasable_id'], 'cart_lines_cart_purchasable_unique');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('cart_lines'), static function (Blueprint $table): void {
            $table->dropUnique('cart_lines_cart_purchasable_unique');
        });
    }
};
