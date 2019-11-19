<?php

namespace Shopper\Framework\Traits\Database;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    /**
     * Truncate Table
     *
     * @param string $table
     * @return bool|void
     */
    protected function truncate($table)
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                return DB::table($table)->truncate();

            case 'pgsql':
                return  DB::statement('TRUNCATE TABLE '.$table.' RESTART IDENTITY CASCADE');

            case 'sqlite': case 'sqlsrv':
            return DB::statement('DELETE FROM '.$table);
        }

        return false;
    }

    /**
     * Truncate multiple tables
     *
     * @param array $tables
     */
    protected function truncateMultiple(array $tables)
    {
        foreach ($tables as $table) {
            $this->truncate($table);
        }
    }
}
