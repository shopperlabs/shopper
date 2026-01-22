<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    /** @var string[] */
    protected array $tables = [
        'inventories',
        'order_addresses',
        'user_addresses',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($this->getTableName($table), static function (Blueprint $blueprint): void {
                $blueprint->string('state')->nullable()->after('city');
            });
        }
    }
};
