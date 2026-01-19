<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('product_variants'), function (Blueprint $table): void {
            $table->dropUnique(['sku']);
            $table->unique(['product_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('product_variants'), function (Blueprint $table): void {
            $table->dropUnique(['product_id', 'sku']);
            $table->unique(['sku']);
        });
    }
};
