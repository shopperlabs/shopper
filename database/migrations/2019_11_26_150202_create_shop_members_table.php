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
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('user_id');
            $table->primary(['user_id', 'shop_id'], 'shop_members_shop_id_user_id_primary');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('shop_id')->references('id')->on($this->getTableName('shops'))->onDelete('CASCADE');
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
