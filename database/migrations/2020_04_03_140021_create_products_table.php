<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateProductsTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('products'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('sku')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->boolean('featured')->default(false);
            $table->double('price')->nullable();
            $table->dateTimeTz('published_at')->default(now());
            $table->boolean('backorder')->default(false);
            $table->boolean('requires_shipping')->default(false);

            $this->addForeignKey($table, 'parent_id', $this->getTableName('products'));
            $this->addForeignKey($table, 'shop_id', $this->getTableName('shops'));
            $this->addForeignKey($table, 'brand_id', $this->getTableName('brands'), true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('products'));
    }
}
