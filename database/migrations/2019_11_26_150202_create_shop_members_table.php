<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateShopMembersTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('shop_members'), function(Blueprint $table) {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'user_id', 'users', false);
            $this->addForeignKey($table, 'shop_id', $this->getTableName('shops'), false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('shop_members'));
    }
}
