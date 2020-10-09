<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateCartItemsTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('cart_items'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name')->nullable()->comment('The product name at the moment of buying');
            $table->string('sku')->nullable()->index();
            $table->morphs('product');
            $table->integer('quantity');
            $table->decimal('unit_price_amount', 10, 2);

            $this->addForeignKey($table, 'cart_id', $this->getTableName('carts'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('cart_items'));
    }
}
