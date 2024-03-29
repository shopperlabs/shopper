<?php

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
        'products',
        'payment_methods',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($this->getTableName($table), function (Blueprint $blueprint) {
                $blueprint->json('metadata')->nullable();
            });
        }
    }
};
