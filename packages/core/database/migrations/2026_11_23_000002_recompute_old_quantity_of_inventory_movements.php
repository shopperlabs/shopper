<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $table = $this->getTableName('inventory_histories');
        $stockBefore = [];

        DB::table($table)
            ->select(['id', 'stockable_type', 'stockable_id', 'inventory_id', 'quantity', 'old_quantity'])
            ->chunkById(1000, function (Collection $movements) use ($table, &$stockBefore): void {
                DB::transaction(function () use ($movements, $table, &$stockBefore): void {
                    foreach ($movements as $movement) {
                        $key = "{$movement->stockable_type}:{$movement->stockable_id}:{$movement->inventory_id}";
                        $oldQuantity = $stockBefore[$key] ?? 0;
                        $stockBefore[$key] = $oldQuantity + (int) $movement->quantity;

                        if ((int) $movement->old_quantity !== $oldQuantity) {
                            DB::table($table)->where('id', $movement->id)->update(['old_quantity' => $oldQuantity]);
                        }
                    }
                });
            });
    }
};
