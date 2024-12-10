<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('channels'), function (Blueprint $table): void {
            $table->boolean('is_enabled')->default(false)->after('is_default');
        });
    }
};
