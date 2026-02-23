<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('payment_methods'), function (Blueprint $table): void {
            $table->string('driver')->nullable()->after('is_enabled');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('payment_methods'), function (Blueprint $table): void {
            $table->dropColumn('driver');
        });
    }
};
