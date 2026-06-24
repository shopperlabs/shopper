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
        $table = $this->getTableName('discountables');

        $idsToKeep = DB::table($table)
            ->selectRaw('MIN(id) as id')
            ->groupBy('discount_id', 'discountable_type', 'discountable_id')
            ->pluck('id');

        if ($idsToKeep->isNotEmpty()) {
            DB::table($table)
                ->whereNotIn('id', $idsToKeep)
                ->delete();
        }

        Schema::table($table, function (Blueprint $table): void {
            $table->unique(
                ['discount_id', 'discountable_type', 'discountable_id'],
                'discountables_discount_target_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('discountables'), function (Blueprint $table): void {
            $table->dropUnique('discountables_discount_target_unique');
        });
    }
};
