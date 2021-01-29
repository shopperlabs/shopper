<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class AddSoftDeletedToProducts extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->getTableName('products'), function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table($this->getTableName('orders'), function (Blueprint $table) {
            $this->addForeignKey($table, 'payment_method_id', $this->getTableName('payment_methods'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->getTableName('products'), function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table($this->getTableName('orders'), function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'payment_method_id']);
        });
    }
}
