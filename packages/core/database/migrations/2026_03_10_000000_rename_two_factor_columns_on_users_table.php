<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->renameColumn('two_factor_secret', 'store_two_factor_secret');
            $table->renameColumn('two_factor_recovery_codes', 'store_two_factor_recovery_codes');
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->renameColumn('store_two_factor_secret', 'two_factor_secret');
            $table->renameColumn('store_two_factor_recovery_codes', 'two_factor_recovery_codes');
        });
    }
};
