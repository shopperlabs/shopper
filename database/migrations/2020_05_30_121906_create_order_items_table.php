<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateOrderItemsTable extends Migration
{
    use MigrationTrait;

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
            $table->decimal('unit_amount', 10, 2);

            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'));
            $this->addForeignKey($table, 'shop_id', $this->getTableName('shops'), false);
        });

        Schema::create($this->getTableName('orders_refunds'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->increments('id');
            $table->longText('refund_reason')->nullable();
            $table->string('refund_amount')->nullable();
            $table->enum('status', ['waiting', 'treatment', 'partial-refund', 'total-refund', 'refused'])->default('treatment');
            $table->longText('notes');
            
            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'));
            $this->addForeignKey($table, 'user_id', $this->getTableName('users'));
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
        Schema::dropIfExists($this->getTableName('order_refunds'));
    }
}
