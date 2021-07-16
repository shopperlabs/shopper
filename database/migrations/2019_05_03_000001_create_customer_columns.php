<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

class CreateCustomerColumns extends Migration
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

            $table->dropColumn('name');
            $table->string('password')->nullable()->change();

            $table->after('id', function ($table) {

                $table->string('first_name')->nullable();
                $table->string('last_name');
                $table->enum('gender', ['male', 'female']);
                $table->string('phone_number')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('avatar_type')->default('gravatar');
                $table->string('avatar_location')->nullable();
                $table->string('timezone')->nullable();
                $table->boolean('opt_in')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->string('last_login_ip')->nullable();

                $table->string('stripe_id')->nullable()->index();
                $table->string('card_brand')->nullable();
                $table->string('card_last_four', 4)->nullable();
                $table->timestamp('trial_ends_at')->nullable();
            });

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
        Schema::table($this->getTableName('users'), function (Blueprint $table) {

            $table->dropColumn([
                'first_name',
                'last_name',
                'gender',
                'phone_number',
                'birth_date',
                'avatar_type',
                'avatar_location',
                'timezone',
                'opt_in',
                'last_login_at',
                'last_login_ip',
                'stripe_id',
                'card_brand',
                'card_last_four',
                'trial_ends_at',
            ]);

            $table->string('name');
            $table->string('password')->change();
            $table->dropSoftDeletes();
        });
    }
}
