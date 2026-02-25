<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('cart_lines'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'cart_id', $this->getTableName('carts'), false);

            $table->morphs('purchasable');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_price_amount');
            $table->jsonb('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('cart_lines'));
    }
};
