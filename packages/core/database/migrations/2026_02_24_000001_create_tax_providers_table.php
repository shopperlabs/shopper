<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('tax_providers'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('identifier')->unique();
            $table->boolean('is_enabled')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('tax_providers'));
    }
};
