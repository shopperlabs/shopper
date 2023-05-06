<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateUsersGeolocationHistoryTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('users_geolocation_history'), function (Blueprint $table) {
            $this->addCommonFields($table, true);

            $table->json('ip_api')->nullable();
            $table->json('extreme_ip_lookup')->nullable();

            $this->addForeignKey($table, 'user_id', $this->getTableName('users'), false);
            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'), true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('users_geolocation_history'));
    }
}
