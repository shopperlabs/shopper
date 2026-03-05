<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('products'), static function (Blueprint $table): void {
            $table->boolean('allow_backorder')->default(false)->after('brand_id');
        });
    }
};
