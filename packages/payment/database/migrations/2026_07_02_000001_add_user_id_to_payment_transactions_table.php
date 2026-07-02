<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('payment_transactions'), static function (Blueprint $table): void {
            $table->unsignedBigInteger('user_id')->nullable()->after('payment_method_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('payment_transactions'), static function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
