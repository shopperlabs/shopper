<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('orders'), function (Blueprint $table): void {
            $this->addForeignKey($table, 'discount_id', $this->getTableName('discounts'));

            $table->after('discount_id', static function (Blueprint $table): void {
                $table->string('discount_code')->nullable();
                $table->string('discount_type', 32)->nullable();
                $table->unsignedInteger('discount_value_at_apply')->nullable();
                $table->char('discount_currency_code', 3)->nullable();
            });
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('orders'), function (Blueprint $table): void {
            $this->removeForeignKeyAndColumn($table, 'discount_id');

            $table->dropColumn([
                'discount_code',
                'discount_type',
                'discount_value_at_apply',
                'discount_currency_code',
            ]);
        });
    }
};
