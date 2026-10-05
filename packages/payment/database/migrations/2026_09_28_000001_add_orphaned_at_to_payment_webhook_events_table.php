<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('payment_webhook_events'), static function (Blueprint $table): void {
            $table->timestamp('orphaned_at')->nullable()->after('processed_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('payment_webhook_events'), static function (Blueprint $table): void {
            $table->dropIndex(['created_at']);
            $table->dropColumn('orphaned_at');
        });
    }
};
