<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('products'), function (Blueprint $table): void {
            $table->dropColumn([
                'old_price_amount',
                'price_amount',
                'cost_amount',
                'backorder',
                'require_shipping',
            ]);
            $this->removeForeignKeyAndColumn($table, 'parent_id');

            $table->after('brand_id', function (Blueprint $table): void {
                $table->text('summary')->nullable();
                $table->string('external_id')->nullable();
            });
        });

        Schema::create($this->getTableName('product_variants'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('name')->index();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->string('ean')->unique()->nullable();
            $table->string('upc')->unique()->nullable();
            $table->boolean('allow_backorder')->default(false);
            $table->unsignedInteger('position')->default(1);
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'));
            $this->addShippingFields($table);
            $table->json('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('product_variants'));
    }
};
