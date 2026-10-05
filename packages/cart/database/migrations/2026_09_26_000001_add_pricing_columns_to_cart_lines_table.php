<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('cart_lines'), static function (Blueprint $table): void {
            $table->after('unit_price_amount', function (Blueprint $table): void {
                $table->boolean('is_custom_price')->default(false);
                $table->json('pricing')->nullable();
            });
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('cart_lines'), static function (Blueprint $table): void {
            $table->dropColumn(['is_custom_price', 'pricing']);
        });
    }
};
