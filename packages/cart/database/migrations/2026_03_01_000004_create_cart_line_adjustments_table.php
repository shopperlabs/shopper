<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('cart_line_adjustments'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'cart_line_id', $this->getTableName('cart_lines'), false);
            $this->addForeignKey($table, 'discount_id', $this->getTableName('discounts'));

            $table->unsignedInteger('amount');
            $table->string('code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('cart_line_adjustments'));
    }
};
