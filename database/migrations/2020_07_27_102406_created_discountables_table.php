<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreatedDiscountablesTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('discountables'), function (Blueprint $table) {
            $this->addCommonFields($table);
            $table->string('condition')->nullable(); // apply_to, eligibility
            $table->morphs('discountable');

            $this->addForeignKey($table, 'discount_id', $this->getTableName('discounts'), false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('discountables'));
    }
}
