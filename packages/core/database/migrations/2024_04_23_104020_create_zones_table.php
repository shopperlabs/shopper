<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('zones'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('name')->unique();
            $table->string('slug')->nullable()->unique();
            $table->string('code')->nullable()->unique();
            $table->boolean('is_enabled')->default(false);
            $table->json('metadata')->nullable();

            $this->addForeignKey($table, 'currency_id', $this->getTableName('currencies'));
        });

        Schema::create($this->getTableName('zone_has_relations'), function (Blueprint $table): void {
            $this->addForeignKey($table, 'zone_id', $this->getTableName('zones'), false);
            $table->morphs('zonable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('zone_has_relations'));
        Schema::dropIfExists($this->getTableName('zones'));
    }
};
