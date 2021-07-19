<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

class CreateOrderItemsTable extends Migration
{
    use Database\Migration;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('order_items'));
    }
}
