<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('carrier_options'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $table->foreignId('carrier_id')->constrained($this->getTableName('carriers'));
            $table->foreignId('zone_id')->constrained($this->getTableName('zones'));

            $table->string('name')->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->integer('price');
            $table->jsonb('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('carrier_options'));
    }
};
