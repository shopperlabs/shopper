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

            $table->string('last_name');
            $table->string('first_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->string('phone_number')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date')->nullable();
            $table->string('avatar_type')->default('gravatar');
            $table->string('avatar_location')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('opt_in')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
        });

        Schema::create($this->getTableName('user_addresses'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('last_name');
            $table->string('first_name');
            $table->string('company_name')->nullable();
            $table->string('street_address');
            $table->string('street_address_plus')->nullable();
            $table->string('zipcode');
            $table->string('city');
            $table->string('phone_number')->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('type', ["billing", "shipping"]);

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
        Schema::dropIfExists($this->getTableName('user_addresses'));
        Schema::dropIfExists($this->getTableName('users'));
    }
}
