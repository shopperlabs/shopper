<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

class CreateLegalsTable extends Migration
{
    use Database\Migration;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('legals'), function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->nullable()->unique();
            $table->longText('content')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('legals'));
    }
}
