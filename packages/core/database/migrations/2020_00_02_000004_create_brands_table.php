<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('brands'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('website')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_enabled')->default(false);

            $this->addSeoFields($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('brands'));
    }
};
