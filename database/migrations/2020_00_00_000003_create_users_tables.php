<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database\MigrationTrait;

class CreateUsersTables extends Migration
{
    use MigrationTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Users & Customers table
         */
        Schema::create($this->getTableName('users'), function (Blueprint $table) {
            $this->addCommonFields($table, true);

            $table->string('lastname');
            $table->string('firstname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('optin');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($this->getTableName('user_addresses'), function (Blueprint $table) {
            $this->addCommonFields($table, true);

            $table->string('lastname');
            $table->string('firstname');
            $table->string('company_name')->nullable();
            $table->string('address');
            $table->string('address_plus')->nullable();
            $table->string('zipcode');
            $table->string('city');
            $table->string('phone_number')->nullable();
            $table->boolean('is_default')->default(0);
            $table->enum('type', ["billing", "shipping"]);
            $table->timestamps();

            $this->addForeignKey($table, 'country_id', $this->getTableName('system_countries'));
            $this->addForeignKey($table, 'user_id', $this->getTableName('users'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('users_addresses'));
        Schema::dropIfExists($this->getTableName('users'));
    }
}
