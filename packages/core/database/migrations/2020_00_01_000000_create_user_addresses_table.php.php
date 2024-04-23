<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('user_addresses'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('last_name');
            $table->string('first_name');
            $table->string('company_name')->nullable();
            $table->string('street_address');
            $table->string('street_address_plus')->nullable();
            $table->string('postal_code');
            $table->string('city');
            $table->string('phone_number')->nullable();
            $table->boolean('shipping_default')->default(false);
            $table->boolean('billing_default')->default(false);
            $table->string('type')->nullable();

            $this->addForeignKey($table, 'country_id', $this->getTableName('countries'));
            $this->addForeignKey($table, 'user_id', 'users', false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('user_addresses'));
    }
};
