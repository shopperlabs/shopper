<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('cart_line_tax_lines'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'cart_line_id', $this->getTableName('cart_lines'), false);
            $this->addForeignKey($table, 'tax_rate_id', $this->getTableName('tax_rates'));

            $table->string('code');
            $table->string('name');
            $table->decimal('rate', 8, 4);
            $table->unsignedInteger('amount')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('cart_line_tax_lines'));
    }
};
