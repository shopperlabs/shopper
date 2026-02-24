<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('tax_rates'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'tax_zone_id', $this->getTableName('tax_zones'), false);

            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('rate', 8, 4)->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_combinable')->default(false);
            $table->jsonb('metadata')->nullable();
        });

        Schema::create($this->getTableName('tax_rate_rules'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'tax_rate_id', $this->getTableName('tax_rates'), false);

            $table->string('reference_type');
            $table->string('reference_id');

            $table->unique(['tax_rate_id', 'reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('tax_rate_rules'));
        Schema::dropIfExists($this->getTableName('tax_rates'));
    }
};
