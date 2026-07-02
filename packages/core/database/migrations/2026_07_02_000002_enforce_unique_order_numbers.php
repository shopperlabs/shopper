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
        $ordersTable = $this->getTableName('orders');

        $duplicatedNumbers = DB::table($ordersTable)
            ->select('number')
            ->groupBy('number')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('number');

        foreach ($duplicatedNumbers as $number) {
            DB::table($ordersTable)
                ->where('number', $number)
                ->orderBy('id')
                ->skip(1)
                ->take(PHP_INT_MAX)
                ->pluck('id')
                ->each(function (int $orderId) use ($ordersTable, $number): void {
                    DB::table($ordersTable)
                        ->where('id', $orderId)
                        ->update(['number' => "{$number}-{$orderId}"]);
                });
        }

        Schema::table($ordersTable, static function (Blueprint $table): void {
            $table->string('number', 32)->nullable()->change();
            $table->unique('number');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('orders'), static function (Blueprint $table): void {
            $table->dropUnique(['number']);
            $table->string('number', 32)->nullable(false)->change();
        });
    }
};
