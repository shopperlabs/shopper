<?php

namespace Shopper\Framework\Traits\Database;

use Illuminate\Database\Schema\Blueprint;

trait MigrationTrait
{
    /**
     * Return table name.
     *
     * @param  string $table
     * @return string
     */
    public function getTableName(string $table): string
    {
        if (config('shopper.table_prefix') !== '') {
            return config('shopper.table_prefix').$table;
        }

        return $table;
    }

    /**
     * Create fields common to all tables.
     *
     * @param  Blueprint $table
     * @param  boolean $hasSoftDelete
     */
    public function addCommonFields(Blueprint $table, bool $hasSoftDelete = false): void
    {
        $table->bigIncrements('id');
        $table->timestamps();

        if ($hasSoftDelete) {
            $table->softDeletes();
        }
    }

    /**
     * Link table to $tableName using $columnName.
     *
     * @param  Blueprint $table          Laravel Blueprint
     * @param  string    $columnName     MySQL table column name
     * @param  string    $tableName      MySQL table name
     * @param  boolean   $fk_nullable    foreign key nullable status
     */
    public function addForeignKey(Blueprint $table, $columnName, $tableName, $fk_nullable = true): void
    {
        if ($fk_nullable) {
            $table->unsignedBigInteger($columnName)->index()->nullable();
            $table->foreign($columnName)->references('id')->on($tableName)->onDelete('set null');
        } else {
            $table->unsignedBigInteger($columnName)->index();
            $table->foreign($columnName)->references('id')->on($tableName)->onDelete('CASCADE');
        }
    }

    /**
     * Remove foreign key using $columnName.
     *
     * @param  Blueprint $table       Laravel Blueprint
     * @param  string    $columnName  Column on the table
     */
    public function removeLink(Blueprint $table, $columnName): void
    {
        $table->dropForeign([$columnName]);
        $table->dropColumn([$columnName]);
    }
}
