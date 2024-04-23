<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('countries'), function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('name_official');
            $table->string('region');
            $table->string('subregion')->nullable();
            $table->string('cca2');
            $table->string('cca3');
            $table->string('flag');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->json('phone_calling_code');
            $table->json('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('countries'));
    }
};
