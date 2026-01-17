<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('carrier_options'), function (Blueprint $table): void {
            $table->dropUnique(['name']);
            $table->unique(['name', 'zone_id']);
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('carrier_options'), function (Blueprint $table): void {
            $table->dropUnique(['name', 'zone_id']);
            $table->unique(['name']);
        });
    }
};
