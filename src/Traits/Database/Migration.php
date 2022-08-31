<?php

namespace Shopper\Framework\Traits\Database;

use Illuminate\Database\Schema\Blueprint;

trait Migration
{
    /**
     * Return table name.
     */
    public function getTableName(string $table): string
    {
        if (config('shopper.table_prefix') !== '') {
            return config('shopper.table_prefix') . $table;
        }

        return $table;
    }

    /**
     * Create fields common to all tables.
     */
    public function addCommonFields(Blueprint $table, bool $hasSoftDelete = false): void
    {
        $table->id();
        $table->timestamps();

        if ($hasSoftDelete) {
            $table->softDeletes();
        }
    }

    /**
     * Create fields common to seo.
     */
    public function addSeoFields(Blueprint $table): void
    {
        $table->string('seo_title', 60)->nullable();
        $table->string('seo_description', 160)->nullable();
    }

    /**
     * Create common fields for shipping.
     */
    public function addShippingFields(Blueprint $table): void
    {
        $table->decimal('weight_value', 10, 5)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('weight_unit')->default('kg');
        $table->decimal('height_value', 10, 5)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('height_unit')->default('cm');
        $table->decimal('width_value', 10, 5)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('width_unit')->default('cm');
        $table->decimal('depth_value', 10, 5)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('depth_unit')->default('cm');
        $table->decimal('volume_value', 10, 5)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('volume_unit')->default('l');
    }

    /**
     * Link table to $tableName using $columnName.
     *
     * @param  Blueprint  $table      Laravel Blueprint
     * @param  string  $columnName MySQL table column name
     * @param  string  $tableName  MySQL table name
     * @param  bool  $nullable   Foreign key nullable status
     */
    public function addForeignKey(Blueprint $table, string $columnName, string $tableName, bool $nullable = true): void
    {
        if ($nullable) {
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
     * @param  Blueprint  $table      Laravel Blueprint
     * @param  string  $columnName Column on the table
     */
    public function removeLink(Blueprint $table, string $columnName): void
    {
        $table->dropForeign([$columnName]);
        $table->dropColumn([$columnName]);
    }
}
