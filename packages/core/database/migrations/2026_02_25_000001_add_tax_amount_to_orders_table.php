<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('orders'), static function (Blueprint $table): void {
            $table->integer('tax_amount')
                ->unsigned()
                ->nullable()
                ->after('price_amount');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('orders'), static function (Blueprint $table): void {
            $table->dropColumn('tax_amount');
        });
    }
};
