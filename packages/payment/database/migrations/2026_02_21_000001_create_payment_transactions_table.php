<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('payment_transactions'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('driver');
            $table->string('type');
            $table->string('status');
            $table->integer('amount');
            $table->string('currency_code', 3);
            $table->string('reference')->nullable()->index();
            $table->jsonb('data')->nullable();
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();

            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'));
            $this->addForeignKey($table, 'payment_method_id', $this->getTableName('payment_methods'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('payment_transactions'));
    }
};
