<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Enum\ExclusivityClass;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('discounts'), static function (Blueprint $table): void {
            $table->string('exclusivity_class')->default(ExclusivityClass::Order->value);
            $table->boolean('combinable')->default(false);
            $table->integer('priority')->default(0);

            $table->index(['is_active', 'exclusivity_class', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('discounts'), static function (Blueprint $table): void {
            $table->dropColumn(['exclusivity_class', 'combinable', 'priority']);
        });
    }
};
