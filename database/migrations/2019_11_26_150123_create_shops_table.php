<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateShopsTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('shops'), function(Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('address')->nullable();
            $table->longText('description')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('cover_url')->nullable();
            $table->bigInteger('size_id')->unsigned();
            $table->bigInteger('owner_id')->unsigned();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('size_id')->references('id')->on($this->getTableName('shop_sizes'))->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('shops'));
    }
}
