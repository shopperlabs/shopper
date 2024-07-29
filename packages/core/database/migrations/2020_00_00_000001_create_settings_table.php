<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('settings'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('key')->unique();
            $table->string('display_name')->nullable();
            $table->json('value')->nullable();
            $table->boolean('locked')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
