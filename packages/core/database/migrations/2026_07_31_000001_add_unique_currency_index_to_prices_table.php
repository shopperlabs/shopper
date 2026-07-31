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
        $prices = $this->getTableName('prices');

        $duplicates = DB::table($prices)
            ->select('priceable_type', 'priceable_id', 'currency_id')
            ->selectRaw('MAX(id) as keep_id')
            ->groupBy('priceable_type', 'priceable_id', 'currency_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table($prices)
                ->where('priceable_type', $duplicate->priceable_type)
                ->where('priceable_id', $duplicate->priceable_id)
                ->where('currency_id', $duplicate->currency_id)
                ->where('id', '<>', $duplicate->keep_id)
                ->delete();
        }

        Schema::table($prices, static function (Blueprint $table): void {
            $table->unique(['priceable_type', 'priceable_id', 'currency_id'], 'prices_priceable_currency_unique');
            $table->index(['priceable_type', 'priceable_id', 'currency_id', 'amount'], 'prices_priceable_currency_amount_index');
        });
    }
};
