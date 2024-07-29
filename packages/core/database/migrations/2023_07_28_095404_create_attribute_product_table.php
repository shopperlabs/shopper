<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('attribute_product'), function (Blueprint $table): void {
            $table->id();
            $this->addForeignKey($table, 'attribute_id', $this->getTableName('attributes'), false);
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
            $table->unsignedBigInteger('attribute_value_id')->index()->nullable();
            $table->foreign('attribute_value_id')->references('id')
                ->on($this->getTableName('attribute_values'))
                ->onDelete('CASCADE');
            $table->longText('attribute_custom_value')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('attribute_product'));
    }
};
