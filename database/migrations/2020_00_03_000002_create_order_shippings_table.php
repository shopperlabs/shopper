<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateOrderShippingTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('order_shippings'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->date('shipped_at');
            $table->date('received_at');
            $table->date('returned_at');
            $table->string('tracking_number')->nullable();
            $table->string('tracking_number_url')->nullable();
            $table->json('voucher')->nullable();

            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'), false);
            $this->addForeignKey($table, 'carrier_id', $this->getTableName('carriers'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('order_shippings'));
    }
}
