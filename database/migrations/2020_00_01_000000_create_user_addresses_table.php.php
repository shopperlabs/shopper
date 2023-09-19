<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateUserAddressesTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
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
            $table->enum('type', ['billing', 'shipping']);

            $this->addForeignKey($table, 'country_id', $this->getTableName('system_countries'));
            $this->addForeignKey($table, 'user_id', $this->getTableName('users'), false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('user_addresses'));
    }
}
