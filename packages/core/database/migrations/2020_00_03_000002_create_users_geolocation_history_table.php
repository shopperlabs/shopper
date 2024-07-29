<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('users_geolocation_history'), function (Blueprint $table): void {
            $this->addCommonFields($table, true);

            $table->json('ip_api')->nullable();
            $table->json('extreme_ip_lookup')->nullable();

            $this->addForeignKey($table, 'user_id', 'users', false);
            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('users_geolocation_history'));
    }
};
