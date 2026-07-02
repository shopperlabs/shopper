<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('carts'), static function (Blueprint $table): void {
            $table->timestamp('calculated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('carts'), static function (Blueprint $table): void {
            $table->dropColumn('calculated_at');
        });
    }
};
