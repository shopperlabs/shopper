<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('order_addresses'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'customer_id', 'users');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('company')->nullable();
            $table->string('street_address');
            $table->string('street_address_plus')->nullable();
            $table->string('postal_code');
            $table->string('city');
            $table->string('phone')->nullable();
            $table->string('country_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_addresses'));
    }
};
