<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('cart_promotions'), function (Blueprint $table): void {
            $table->string('type')->nullable();
            $table->unsignedInteger('value')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('cart_promotions'), function (Blueprint $table): void {
            $table->dropColumn(['type', 'value', 'campaign_id']);
        });
    }
};
