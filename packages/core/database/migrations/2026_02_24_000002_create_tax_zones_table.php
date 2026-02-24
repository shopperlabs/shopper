<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('tax_zones'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'parent_id', $this->getTableName('tax_zones'));
            $this->addForeignKey($table, 'provider_id', $this->getTableName('tax_providers'));

            $table->string('name');
            $table->string('country_code', 2);
            $table->string('province_code')->nullable();
            $table->boolean('is_tax_inclusive')->default(false);
            $table->jsonb('metadata')->nullable();

            $table->unique(['country_code', 'province_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('tax_zones'));
    }
};
