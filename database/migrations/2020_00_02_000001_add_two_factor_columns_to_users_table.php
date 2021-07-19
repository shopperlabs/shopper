<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

class AddTwoFactorColumnsToUsersTable extends Migration
{
    use Database\Migration;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->getTableName('users'), function (Blueprint $table) {
            $table->text('two_factor_secret')
                    ->after('password')
                    ->nullable();

            $table->text('two_factor_recovery_codes')
                    ->after('two_factor_secret')
                    ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->getTableName('users'), function (Blueprint $table) {
            $table->dropColumn('two_factor_secret', 'two_factor_recovery_codes');
        });
    }
}
