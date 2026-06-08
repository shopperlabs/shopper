<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('discountables'), function (Blueprint $table): void {
            $table->unique(
                ['discount_id', 'discountable_type', 'discountable_id'],
                'discountables_discount_target_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('discountables'), function (Blueprint $table): void {
            $table->dropUnique('discountables_discount_target_unique');
        });
    }
};
