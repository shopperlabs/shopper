<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('zones'), function (Blueprint $table): void {
            $table->jsonb('metadata')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('zones'), function (Blueprint $table): void {
            $table->json('metadata')->nullable()->change();
        });
    }
};
