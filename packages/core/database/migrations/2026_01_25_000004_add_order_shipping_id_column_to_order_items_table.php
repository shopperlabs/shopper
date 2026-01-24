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
            $this->addForeignKey($table, 'order_shipping_id', $this->getTableName('order_shipping'));
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('order_items'), function (Blueprint $table): void {
            $table->dropConstrainedForeignId('order_shipping_id');
        });
    }
};
