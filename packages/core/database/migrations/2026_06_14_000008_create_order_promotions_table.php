<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('order_promotions'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'), false);
            $this->addForeignKey($table, 'discount_id', $this->getTableName('discounts'));

            $table->string('code')->nullable();
            $table->string('type', 32);
            $table->unsignedInteger('value_at_apply');
            $table->unsignedInteger('amount');
            $table->char('currency_code', 3);

            $table->index(['discount_id', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_promotions'));
    }
};
