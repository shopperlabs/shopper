<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateMediaTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('media'), function (Blueprint $table) {
            $this->addCommonFields($table);
            $table->string('disk_name');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('content_type');
            $table->string('field')->nullable();
            $table->boolean('is_public')->default(true);
            $table->smallInteger('sort_order')->default(1);
            $table->nullableMorphs('mediatable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('media'));
    }
}
