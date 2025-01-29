<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('order_refunds'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->text('reason')->nullable();
            $table->integer('amount');
            $table->string('currency');
            $table->string('status');
            $table->text('notes')->nullable();

            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'), false);
            $this->addForeignKey($table, 'user_id', 'users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_refunds'));
    }
};
