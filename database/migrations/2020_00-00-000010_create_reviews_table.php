<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateReviewsTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('reviews'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->integer('rating');
            $table->integer('customer_service_rating')->nullable();
            $table->integer('quality_rating')->nullable();
            $table->integer('friendly_rating')->nullable();
            $table->integer('pricing_rating')->nullable();
            $table->enum('recommend', ['Yes', 'No']);
            $table->string('content')->nullable();
            $table->boolean('approved')->default(false);
            $table->morphs('reviewrateable');
            $table->morphs('author');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('reviews'));
    }
}
