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
        $tableName = $this->getTableName('currencies');

        Schema::table($tableName, function (Blueprint $table): void {
            $table->string('code', 10)->unique()->change();
            $table->boolean('is_enabled')->default(true);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN exchange_rate TYPE DECIMAL(10,2) USING exchange_rate::numeric(10,2)");
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN exchange_rate DROP NOT NULL");
        } else {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->decimal('exchange_rate', 10)->nullable()->change();
            });
        }
    }
};
