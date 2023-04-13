<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateOrderItemsTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('order_items'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name')->nullable()->comment('The product name at the moment of buying');
            $table->string('sku')->nullable()->index();
            $table->morphs('product');
            $table->integer('quantity');
            $table->integer('unit_price_amount');

            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_items'));
    }
}
