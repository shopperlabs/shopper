<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('orders'), function (Blueprint $table): void {
            $this->addCommonFields($table, true);

            $table->string('number', 32);
            $table->integer('price_amount')->nullable();
            $table->string('status', 32);
            $table->string('currency_code');
            $table->text('notes')->nullable();

            $this->addForeignKey($table, 'parent_order_id', $this->getTableName('orders'));
            $this->addForeignKey($table, 'payment_method_id', $this->getTableName('payment_methods'));
            $this->addForeignKey($table, 'channel_id', $this->getTableName('channels'));
            $this->addForeignKey($table, 'customer_id', 'users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('orders'));
    }
};
