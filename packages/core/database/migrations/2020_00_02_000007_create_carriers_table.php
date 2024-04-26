<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('carriers'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('logo')->nullable();
            $table->string('link_url')->nullable();
            $table->string('description')->nullable();
            $table->integer('shipping_amount')->nullable();
            $table->boolean('is_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('carriers'));
    }
};
