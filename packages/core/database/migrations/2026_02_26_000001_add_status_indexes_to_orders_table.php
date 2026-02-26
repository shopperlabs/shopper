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
            $table->index(['payment_status', 'currency_code', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('orders'), static function (Blueprint $table): void {
            $table->dropIndex(['payment_status', 'currency_code', 'created_at']);
            $table->dropIndex(['status', 'created_at']);
        });
    }
};
