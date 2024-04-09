<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    protected array $tables = [
        'brands',
        'channels',
        'categories',
        'carriers',
        'collections',
        'discounts',
        'orders',
        'products',
        'payment_methods',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($this->getTableName($table), function (Blueprint $blueprint): void {
                $blueprint->json('metadata')->nullable();
            });
        }
    }
};
