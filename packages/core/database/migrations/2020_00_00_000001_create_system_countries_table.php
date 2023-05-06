<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateSystemCountriesTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('system_countries'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_official');
            $table->string('cca2');
            $table->string('cca3');
            $table->string('flag');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->json('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('system_countries'));
    }
}
