<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('cart_addresses'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'cart_id', $this->getTableName('carts'), false);
            $this->addForeignKey($table, 'country_id', $this->getTableName('countries'));

            $table->string('type');
            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('company')->nullable();
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code');
            $table->string('phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('cart_addresses'));
    }
};
