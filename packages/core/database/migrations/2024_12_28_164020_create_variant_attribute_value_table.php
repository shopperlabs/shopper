<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('attribute_value_product_variant'), function (Blueprint $table): void {
            $table->id();
            $this->addForeignKey($table, 'value_id', $this->getTableName('attribute_values'), false);
            $this->addForeignKey($table, 'variant_id', $this->getTableName('product_variants'), false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('attribute_value_product_variant'));
    }
};
