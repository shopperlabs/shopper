<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateAttributesTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('attributes'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('type');
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_filterable')->default(false);
        });

        Schema::create($this->getTableName('attribute_values'), function (Blueprint $table) {
            $table->id();
            $table->string('value', 50);
            $table->string('key')->unique();
            $table->unsignedSmallInteger('position')->nullable()->default(1);
            $this->addForeignKey($table, 'attribute_id', $this->getTableName('attributes'), false);
        });

        Schema::create($this->getTableName('product_attributes'), function (Blueprint $table) {
            $table->id();
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
            $this->addForeignKey($table, 'attribute_id', $this->getTableName('attributes'), false);
        });

        Schema::create($this->getTableName('attribute_value_product_attribute'), function (Blueprint $table) {
            $table->id();
            $this->addForeignKey($table, 'attribute_value_id', $this->getTableName('attribute_values'));
            $this->addForeignKey($table, 'product_attribute_id', $this->getTableName('product_attributes'), false);
            $table->longText('product_custom_value')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('attribute_value_product_attribute'));
        Schema::dropIfExists($this->getTableName('product_attributes'));
        Schema::dropIfExists($this->getTableName('attribute_values'));
        Schema::dropIfExists($this->getTableName('attributes'));
    }
}
