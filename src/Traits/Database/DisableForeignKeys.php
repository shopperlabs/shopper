<?php

namespace Shopper\Framework\Traits\Database;

use Illuminate\Support\Facades\DB;

trait DisableForeignKeys
{
    /**
     * Command to disable foreign key for each database management.
     *
     * @var array
     */
    private $commands = [
        'mysql' => [
            'enable' => 'SET FOREIGN_KEY_CHECKS=1;',
            'disable' => 'SET FOREIGN_KEY_CHECKS=0;',
        ],
        'sqlite' => [
            'enable' => 'PRAGMA foreign_keys = ON;',
            'disable' => 'PRAGMA foreign_keys = OFF;',
        ],
        'sqlsrv' => [
            'enable' => 'EXEC sp_msforeachtable @command1="print \'?\'", @command2="ALTER TABLE ? WITH CHECK CHECK CONSTRAINT all";',
            'disable' => 'EXEC sp_msforeachtable "ALTER TABLE ? NOCHECK CONSTRAINT all";',
        ],
        'pgsql' => [
            'enable' => 'SET CONSTRAINTS ALL IMMEDIATE;',
            'disable' => 'SET CONSTRAINTS ALL DEFERRED;',
        ],
    ];

    /**
     * Disable foreign key checks for current db driver.
     */
    protected function disableForeignKeys()
    {
        DB::statement($this->getDisableStatement());
    }

    /**
     * Enable foreign key checks for current db driver.
     */
    protected function enableForeignKeys()
    {
        DB::statement($this->getEnableStatement());
    }

    /**
     * Return current driver enable command.
     */
    private function getEnableStatement()
    {
        return $this->getDriverCommands()['enable'];
    }

    /**
     * Return current driver disable command.
     */
    private function getDisableStatement()
    {
        return $this->getDriverCommands()['disable'];
    }

    /**
     * Returns command array for current db driver.
     */
    private function getDriverCommands()
    {
        return $this->commands[DB::getDriverName()];
    }
}
