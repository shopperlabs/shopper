<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits\Database;

use Illuminate\Support\Facades\DB;

trait DisableForeignKeys
{
    /**
     * Command to disable foreign key for each database management.
     *
     * @var array<string, string[]>
     */
    private array $commands = [
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

    protected function disableForeignKeys(): void
    {
        DB::statement($this->getDisableStatement());
    }

    protected function enableForeignKeys(): void
    {
        DB::statement($this->getEnableStatement());
    }

    private function getEnableStatement(): string
    {
        return $this->getDriverCommands()['enable'];
    }

    private function getDisableStatement(): string
    {
        return $this->getDriverCommands()['disable'];
    }

    private function getDriverCommands(): array
    {
        return $this->commands[DB::getDriverName()];
    }
}
