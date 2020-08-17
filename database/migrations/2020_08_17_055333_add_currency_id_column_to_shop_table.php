<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class AddCurrencyIdColumnToShopTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->getTableName('shops'), function (Blueprint $table) {
            $this->addForeignKey($table, 'currency_id', 'currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->getTableName('shops'), function (Blueprint $table) {
            $this->removeLink($table, 'currency_id');
        });
    }
}
