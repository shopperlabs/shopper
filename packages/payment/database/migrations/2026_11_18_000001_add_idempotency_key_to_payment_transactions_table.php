<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('payment_transactions'), static function (Blueprint $table): void {
            $table->string('idempotency_key')->nullable()->after('reference');

            $table->unique(['order_id', 'idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('payment_transactions'), static function (Blueprint $table): void {
            $table->dropUnique(['order_id', 'idempotency_key']);
            $table->dropColumn('idempotency_key');
        });
    }
};
