<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('carts'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'customer_id', 'users');
            $this->addForeignKey($table, 'channel_id', $this->getTableName('channels'));
            $this->addForeignKey($table, 'zone_id', $this->getTableName('zones'));

            $table->string('currency_code');
            $table->string('coupon_code')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->jsonb('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('carts'));
    }
};
