<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('product_tags'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('product_tags'));
    }
};
