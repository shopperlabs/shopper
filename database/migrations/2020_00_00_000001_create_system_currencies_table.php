<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateSystemCurrenciesTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('system_currencies'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->index();
            $table->string('symbol', 25);
            $table->string('format', 50);
            $table->string('exchange_rate')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('system_currencies'));
    }
}
