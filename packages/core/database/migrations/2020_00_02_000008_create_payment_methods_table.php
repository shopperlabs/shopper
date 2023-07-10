<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('payment_methods'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('logo')->nullable();
            $table->string('link_url')->nullable();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('payment_methods'));
    }
};
