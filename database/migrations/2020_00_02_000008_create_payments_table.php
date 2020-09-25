<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreatePaymentsTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('payments'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('logo')->nullable();
            $table->string('link_url')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('payments'));
    }
}
