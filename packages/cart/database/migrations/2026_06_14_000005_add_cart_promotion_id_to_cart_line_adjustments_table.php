<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('cart_line_adjustments'), function (Blueprint $table): void {
            $this->addForeignKey($table, 'cart_promotion_id', $this->getTableName('cart_promotions'));
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('cart_line_adjustments'), function (Blueprint $table): void {
            $this->removeForeignKeyAndColumn($table, 'cart_promotion_id');
        });
    }
};
