<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('discounts'), function (Blueprint $table): void {
            $this->addForeignKey($table, 'zone_id', $this->getTableName('zones'));
        });
    }
};
