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
            $table->integer('security_stock')->default(0);
            $table->longText('description')->nullable();
            $table->boolean('featured')->default(false);
            $table->decimal('price', 12, 4)->nullable();
            $table->decimal('min_price', 12, 4)->nullable();
            $table->decimal('max_price', 12, 4)->nullable();
            $table->dateTimeTz('published_at')->default(now());
            $table->boolean('backorder')->default(false);
            $table->boolean('requires_shipping')->default(false);

            $table->decimal('weight_value', 10, 5)->default(0.00)->unsigned();
            $table->string('weight_unit')->default('kg');
            $table->decimal('height_value', 10, 5)->default(0.00)->unsigned();
            $table->string('height_unit')->default('cm');
            $table->decimal('width_value', 10, 5)->default(0.00)->unsigned();
            $table->string('width_unit')->default('cm');
            $table->decimal('depth_value', 10, 5)->default(0.00)->unsigned();
            $table->string('depth_unit')->default('cm');
            $table->decimal('volume_value', 10, 5)->default(0.00)->unsigned();
            $table->string('volume_unit')->default('l');

            $this->addForeignKey($table, 'parent_id', $this->getTableName('products'));
            $this->addForeignKey($table, 'shop_id', $this->getTableName('shops'), false);
            $this->addForeignKey($table, 'brand_id', $this->getTableName('brands'));
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
