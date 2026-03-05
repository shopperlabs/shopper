<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('order_items'), static function (Blueprint $table): void {
            $table->integer('tax_amount')
                ->unsigned()
                ->default(0)
                ->after('unit_price_amount');
            $table->integer('discount_amount')
                ->unsigned()
                ->default(0);
        });
    }
};
