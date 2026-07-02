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
        Schema::create($this->getTableName('stock_levels'), function (Blueprint $table): void {
            $table->id();
            $table->string('stockable_type');
            $table->unsignedBigInteger('stockable_id');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $this->addForeignKey($table, 'inventory_id', $this->getTableName('inventories'));

            $table->unique(['stockable_type', 'stockable_id', 'inventory_id']);
        });

        $this->backfillFromLedger();
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('stock_levels'));
    }

    private function backfillFromLedger(): void
    {
        $now = now();

        DB::table($this->getTableName('inventory_histories'))
            ->selectRaw('stockable_type, stockable_id, inventory_id, SUM(quantity) as quantity')
            ->groupBy('stockable_type', 'stockable_id', 'inventory_id')
            ->orderBy('stockable_type')
            ->orderBy('stockable_id')
            ->orderBy('inventory_id')
            ->chunk(1000, function ($levels) use ($now): void {
                DB::table($this->getTableName('stock_levels'))->insert(
                    $levels->map(fn (object $level): array => [
                        'stockable_type' => $level->stockable_type,
                        'stockable_id' => $level->stockable_id,
                        'inventory_id' => $level->inventory_id,
                        'quantity' => (int) $level->quantity,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all(),
                );
            });
    }
};
