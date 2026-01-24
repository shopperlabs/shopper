<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(shopper_table('attribute_values'), function (Blueprint $table): void {
            $table->dropUnique(['key']);
            $table->unique(['attribute_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::table(shopper_table('attribute_values'), function (Blueprint $table): void {
            $table->dropUnique(['attribute_id', 'key']);
            $table->unique('key');
        });
    }
};
