<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('attributes'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('type');
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_filterable')->default(false);
        });

        Schema::create($this->getTableName('attribute_values'), function (Blueprint $table): void {
            $table->id();
            $table->string('value', 50);
            $table->string('key')->unique();
            $table->unsignedSmallInteger('position')->nullable()->default(1);
            $this->addForeignKey($table, 'attribute_id', $this->getTableName('attributes'), false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('attribute_values'));
        Schema::dropIfExists($this->getTableName('attributes'));
    }
};
