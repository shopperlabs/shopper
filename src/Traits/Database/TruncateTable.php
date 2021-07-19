<?php

namespace Shopper\Framework\Traits\Database;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    protected function truncate(string $table)
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                return DB::table($table)->truncate();

            case 'pgsql':
                return DB::statement('TRUNCATE TABLE '.$table.' RESTART IDENTITY CASCADE');

            case 'sqlite':
                return DB::statement('DELETE FROM '.$table);
        }

        return false;
    }

    protected function truncateMultiple(array $tables)
    {
        foreach ($tables as $table) {
            $this->truncate($table);
        }
    }
}
