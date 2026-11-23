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
        $stockLevels = $this->getTableName('stock_levels');

        DB::table($stockLevels)->whereNull('inventory_id')->delete();

        Schema::table($stockLevels, function (Blueprint $table): void {
            $table->dropForeign(['inventory_id']);

            $table->unsignedBigInteger('inventory_id')->nullable(false)->change();

            $table->foreign('inventory_id')
                ->references('id')
                ->on($this->getTableName('inventories'))
                ->cascadeOnDelete();
        });
    }
};
