<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits\Database;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    protected function truncate(string $table): ?bool
    {
        return match (DB::getDriverName()) {
            'mysql' => DB::table($table)->truncate(),
            'pgsql' => DB::statement('TRUNCATE TABLE ' . $table . ' RESTART IDENTITY CASCADE'),
            'sqlite' => DB::statement('DELETE FROM ' . $table),
        };
    }

    protected function truncateMultiple(array $tables): void
    {
        foreach ($tables as $table) {
            $this->truncate($table);
        }
    }
}
