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
        Schema::table($this->getTableName('discounts'), static function (Blueprint $table): void {
            $table->string('trigger')->default(PromotionSource::Code->value)->after('code');
            $table->index(['trigger', 'is_active']);

            // Automatic discounts carry no code; multiple NULLs stay valid under the unique index.
            $table->string('code')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('discounts'), static function (Blueprint $table): void {
            $table->dropColumn('trigger');
            $table->string('code')->nullable(false)->change();
        });
    }
};
