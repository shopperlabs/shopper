<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('products'), function (Blueprint $table): void {
            $this->addCommonFields($table, true);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->integer('security_stock')->nullable()->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->integer('old_price_amount')->nullable();
            $table->integer('price_amount')->nullable();
            $table->integer('cost_amount')->nullable();
            $table->string('type')->nullable();
            $table->boolean('backorder')->default(false);
            $table->boolean('requires_shipping')->default(false);
            $table->dateTimeTz('published_at')->default(now());

            $this->addSeoFields($table);
            $this->addShippingFields($table);

            $this->addForeignKey($table, 'parent_id', $this->getTableName('products'));
            $this->addForeignKey($table, 'brand_id', $this->getTableName('brands'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('products'));
    }
};
