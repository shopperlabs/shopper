<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('carriers'), function (Blueprint $table): void {
            $table->string('driver')->nullable()->after('slug');
            $table->dropColumn('logo');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('carriers'), function (Blueprint $table): void {
            $table->dropColumn('driver');
            $table->string('logo')->nullable()->after('slug');
        });
    }
};
