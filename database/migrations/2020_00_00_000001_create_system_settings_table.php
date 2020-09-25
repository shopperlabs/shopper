<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateSystemSettingsTable extends Migration
{
    use MigrationTrait;

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
        Schema::create($this->getTableName('system_settings'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->id();
            $table->string('key')->unique();
            $table->string('display_name');
            $table->json('value');
            $table->boolean('locked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}
