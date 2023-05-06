<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateSystemSettingsTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('system_settings'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('key')->unique();
            $table->string('display_name')->nullable();
            $table->json('value')->nullable();
            $table->boolean('locked')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
}
