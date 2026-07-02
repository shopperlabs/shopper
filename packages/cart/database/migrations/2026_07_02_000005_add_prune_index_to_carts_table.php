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
            $table->index(['completed_at', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('carts'), static function (Blueprint $table): void {
            $table->dropIndex(['completed_at', 'updated_at']);
        });
    }
};
