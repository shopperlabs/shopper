<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('order_items'), function (Blueprint $table): void {
            $this->addForeignKey($table, 'tax_rate_id', $this->getTableName('tax_rates'));

            $table->integer('tax_amount')
                ->unsigned()
                ->default(0)
                ->after('unit_price_amount');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('order_items'), function (Blueprint $table): void {
            $this->removeForeignKeyAndColumn($table, 'tax_rate_id');

            $table->dropColumn('tax_amount');
        });
    }
};
