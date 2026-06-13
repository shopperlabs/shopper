<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('carts'), function (Blueprint $table): void {
            $this->addForeignKey($table, 'payment_method_id', $this->getTableName('payment_methods'));
            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'));

            $table->string('shipping_option_id')->nullable();
            $table->integer('shipping_amount')->nullable();
            $table->jsonb('payment_session')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('carts'), function (Blueprint $table): void {
            $this->removeForeignKeyAndColumn($table, 'payment_method_id');
            $this->removeForeignKeyAndColumn($table, 'order_id');

            $table->dropColumn(['shipping_option_id', 'shipping_amount', 'payment_session']);
        });
    }
};
