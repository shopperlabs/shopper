<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateCollectionProductTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }
}
