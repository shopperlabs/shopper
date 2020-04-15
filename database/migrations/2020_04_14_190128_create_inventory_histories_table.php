<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateInventoryHistoriesTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('inventory_histories'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->morphs('stockable');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('quantity');
            $table->integer('old_quantity')->default(0);
            $table->text('event')->nullable();
            $table->text('description')->nullable();

            $this->addForeignKey($table, 'inventory_id', $this->getTableName('inventories'), false);
            $this->addForeignKey($table, 'shop_id', $this->getTableName('shops'), false);
            $this->addForeignKey($table, 'user_id', 'users', false);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('inventory_histories'));
    }
}
