<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

class CreateProductHasRelationsTable extends Migration
{
    use Database\Migration;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_has_relations', function (Blueprint $table) {
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
            $table->morphs('productable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_has_relations');
    }
}
