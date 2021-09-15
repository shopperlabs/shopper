<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

class CreateSystemCountriesTable extends Migration
{
    use Database\Migration;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * System settings table
         */
        Schema::create($this->getTableName('system_countries'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_official');
            $table->string('cca2');
            $table->string('cca3');
            $table->string('flag');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->json('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('system_countries'));
    }
}
