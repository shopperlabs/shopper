<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('product_has_relations', function (Blueprint $table): void {
            $this->addForeignKey($table, 'product_id', $this->getTableName('products'), false);
            $table->morphs('productable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_has_relations');
    }
};
