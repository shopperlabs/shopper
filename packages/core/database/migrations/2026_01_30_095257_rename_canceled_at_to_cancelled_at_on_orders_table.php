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
            $table->renameColumn('canceled_at', 'cancelled_at');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('orders'), static function (Blueprint $table): void {
            $table->renameColumn('cancelled_at', 'canceled_at');
        });
    }
};
