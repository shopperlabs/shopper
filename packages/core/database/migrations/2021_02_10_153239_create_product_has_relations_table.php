<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateProductHasRelationsTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create('product_has_relations', function (Blueprint $table) {
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
            $table->morphs('productable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_has_relations');
    }
}
