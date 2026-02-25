<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('order_tax_lines'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->morphs('taxable');
            $table->string('code');
            $table->string('name');
            $table->decimal('rate', 8, 4);
            $table->integer('amount')->unsigned()->default(0);

            $this->addForeignKey($table, 'tax_rate_id', $this->getTableName('tax_rates'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_tax_lines'));
    }
};
