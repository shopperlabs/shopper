<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('order_shipping'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->date('shipped_at');
            $table->date('received_at');
            $table->date('returned_at');
            $table->string('tracking_number')->nullable();
            $table->string('tracking_number_url')->nullable();
            $table->json('voucher')->nullable();

            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'), false);
            $this->addForeignKey($table, 'carrier_id', $this->getTableName('carriers'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_shipping'));
    }
};
