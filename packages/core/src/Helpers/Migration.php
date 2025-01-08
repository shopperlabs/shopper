<?php

declare(strict_types=1);

namespace Shopper\Core\Helpers;

use Illuminate\Database\Migrations\Migration as BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Volume;
use Shopper\Core\Enum\Dimension\Weight;

abstract class Migration extends BaseMigration
{
    protected string $prefix = '';

    public function __construct()
    {
        $this->prefix = config('shopper.core.table_prefix');
    }

    public function getTableName(string $table): string
    {
        return $this->prefix . $table;
    }

    public function addCommonFields(Blueprint $table, bool $hasSoftDelete = false): void
    {
        $table->id();
        $table->timestamps();

        if ($hasSoftDelete) {
            $table->softDeletes();
        }
    }

    public function addSeoFields(Blueprint $table): void
    {
        $table->string('seo_title', 60)->nullable();
        $table->string('seo_description', 160)->nullable();
    }

    public function addShippingFields(Blueprint $table): void
    {
        $table->string('weight_unit')->default(Weight::KG());
        $table->decimal('weight_value', 10)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('height_unit')->default(Length::CM());
        $table->decimal('height_value', 10)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('width_unit')->default(Length::CM());
        $table->decimal('width_value', 10)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('depth_unit')->default(Length::CM());
        $table->decimal('depth_value', 10)->nullable()
            ->default(0.00)
            ->unsigned();
        $table->string('volume_unit')->default(Volume::L());
        $table->decimal('volume_value', 10)->nullable()
            ->default(0.00)
            ->unsigned();
    }

    public function addForeignKey(Blueprint $table, string $column, string $tableName, bool $nullable = true): void
    {
        if ($nullable) {
            $table->unsignedBigInteger($column)->index()->nullable();
            $table->foreign($column)->references('id')->on($tableName)->onDelete('set null');
        } else {
            $table->unsignedBigInteger($column)->index();
            $table->foreign($column)->references('id')->on($tableName)->onDelete('CASCADE');
        }
    }

    public function removeForeignKeyAndColumn(Blueprint $table, string $column): void
    {
        $table->dropForeign([$column]);
        $table->dropIndex([$column]);
        $table->dropColumn($column);
    }
}
