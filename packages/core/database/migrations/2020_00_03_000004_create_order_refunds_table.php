<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateOrderRefundsTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('order_refunds'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->longText('refund_reason')->nullable();
            $table->string('refund_amount')->nullable();
            $table->enum('status', ['pending', 'treatment', 'partial-refund', 'refunded', 'cancelled', 'rejected'])->default('pending');
            $table->longText('notes');

            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'), false);
            $this->addForeignKey($table, 'user_id', $this->getTableName('users'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_refunds'));
    }
}
