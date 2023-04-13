<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateCollectionsTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('collections'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->enum('type', ['manual', 'auto']);
            $table->string('sort')->nullable(); // defaults: ['best_selling', 'alpha_asc', 'alpha_desc', 'price_desc', 'price_asc', 'created_desc', 'created_asc', 'manual']
            $table->enum('match_conditions', ['all', 'any'])->nullable();
            $table->dateTimeTz('published_at')->default(now());

            $this->addSeoFields($table);
        });

        Schema::create($this->getTableName('collection_rules'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('rule'); // defaults: ['product_title', 'product_price', 'compare_at_price', 'inventory_stock', 'product_brand', 'product_category']
            $table->string('operator'); // defaults: ['equals_to', 'not_equals_to', 'less_than', 'greater_than', 'starts_with', 'ends_with', 'contains', 'not_contains']
            $table->string('value');

            $this->addForeignKey($table, 'collection_id', $this->getTableName('collections'), false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('collection_rules'));
        Schema::dropIfExists($this->getTableName('collections'));
    }
}
