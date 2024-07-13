<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('orders'), function (Blueprint $table): void {
            $table->after('customer_id', function (Blueprint $table): void {
                $this->addForeignKey($table, 'zone_id', $this->getTableName('zones'));
                $this->addForeignKey($table, 'billing_address_id', $this->getTableName('order_addresses'));
                $this->addForeignKey($table, 'shipping_address_id', $this->getTableName('order_addresses'));
                $this->addForeignKey($table, 'shipping_option_id', $this->getTableName('carrier_options'));
            });

            $table->timestamp('canceled_at')->nullable();
        });
    }
};
