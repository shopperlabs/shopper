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
            $table->string('barcode')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->integer('security_stock')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->integer('old_price_amount')->nullable();
            $table->integer('price_amount')->nullable();
            $table->integer('cost_amount')->nullable();
            $table->enum('type', ['deliverable', 'downloadable'])->nullable();
            $table->boolean('backorder')->default(false);
            $table->boolean('requires_shipping')->default(false);
            $table->dateTimeTz('published_at')->default(now())->nullable();

            $this->addSeoFields($table);
            $this->addShippingFields($table);

            $this->addForeignKey($table, 'parent_id', $this->getTableName('products'));
            $this->addForeignKey($table, 'brand_id', $this->getTableName('brands'));
        });

        Schema::create($this->getTableName('category_product'), function (Blueprint $table) {
            $this->addForeignKey($table, 'category_id', $this->getTableName('categories'), false);
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
        });

        Schema::create($this->getTableName('channel_product'), function (Blueprint $table) {
            $this->addForeignKey($table, 'channel_id', $this->getTableName('channels'), false);
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
        });

        Schema::create($this->getTableName('collection_product'), function (Blueprint $table) {
            $this->addForeignKey($table, 'collection_id', $this->getTableName('collections'), false);
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('collection_product'));
        Schema::dropIfExists($this->getTableName('channel_product'));
        Schema::dropIfExists($this->getTableName('category_product'));
        Schema::dropIfExists($this->getTableName('products'));
    }
}
