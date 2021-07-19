<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

class CreateBrandsTable extends Migration
{
    use Database\Migration;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('brands'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('website')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_enabled')->default(false);

            $this->addSeoFields($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('brands'));
    }
}
