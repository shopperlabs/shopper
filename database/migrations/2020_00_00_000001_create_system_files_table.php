<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateSystemFilesTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('system_files'), function (Blueprint $table) {
            $this->addCommonFields($table);
        
            $table->string('disk_name');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('content_type');
            $table->boolean('is_public')->default(true);
            $table->smallInteger('position')->default(1);
            $table->nullableMorphs('filetable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('system_files'));
    }
}
