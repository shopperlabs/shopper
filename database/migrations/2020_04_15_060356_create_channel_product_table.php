<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateChannelProductTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('channel_product'), function (Blueprint $table) {
            $this->addForeignKey($table, 'channel_id', $this->getTableName('channels'), false);
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
        Schema::dropIfExists($this->getTableName('channel_product'));
    }
}
