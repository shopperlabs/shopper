<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateDiscountsTable extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('discounts'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->boolean('active')->default(false);
            $table->string('code')->unique()->index();
            $table->string('type'); // Percentage, Fix Amount
            $table->integer('value');
            $table->string('apply_to'); // order, products
            $table->string('min_required'); // none, price, qte items
            $table->string('min_required_value')->nullable();
            $table->string('eligibility'); // everyone, customers
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_limit_per_user')->nullable();
            $table->dateTime('date_start');
            $table->dateTime('date_end')->nullable();

            $this->addForeignKey($table, 'shop_id', $this->getTableName('shops'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('discounts'));
    }
}
