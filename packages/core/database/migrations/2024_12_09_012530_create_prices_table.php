<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('prices'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->morphs('priceable');
            $table->integer('amount')->unsigned()->index()->nullable();
            $table->integer('compare_amount')->unsigned()->nullable();
            $table->integer('cost_amount')->unsigned()->nullable();
            $this->addForeignKey($table, 'currency_id', $this->getTableName('currencies'), false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('prices'));
    }
};
