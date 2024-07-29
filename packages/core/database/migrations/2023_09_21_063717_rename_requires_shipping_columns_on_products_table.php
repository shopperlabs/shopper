<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('products'), function (Blueprint $table): void {
            $table->renameColumn(from: 'requires_shipping', to: 'require_shipping');
        });
    }
};
