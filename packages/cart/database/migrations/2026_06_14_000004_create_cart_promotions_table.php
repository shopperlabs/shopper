<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('cart_promotions'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'cart_id', $this->getTableName('carts'), false);
            $this->addForeignKey($table, 'discount_id', $this->getTableName('discounts'));

            $table->string('source')->default(PromotionSource::Code->value);
            $table->string('code')->nullable();
            $table->unsignedInteger('computed_amount')->default(0);
            $table->unsignedInteger('sequence')->default(0);

            $table->unique(['cart_id', 'discount_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('cart_promotions'));
    }
};
