<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateOrdersTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('orders'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('number', 32);
            $table->integer('price_amount')->nullable();
            $table->string('status', 32);
            $table->string('currency');
            $table->integer('shipping_total')->nullable();
            $table->string('shipping_method')->nullable();
            $table->text('notes')->nullable();

            $this->addForeignKey($table, 'parent_order_id', $this->getTableName('orders'));
            $this->addForeignKey($table, 'shipping_address_id', $this->getTableName('user_addresses'));
            $this->addForeignKey($table, 'user_id', $this->getTableName('users'));
            $this->addForeignKey($table, 'payment_method_id', $this->getTableName('payment_methods'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('orders'));
    }
}
