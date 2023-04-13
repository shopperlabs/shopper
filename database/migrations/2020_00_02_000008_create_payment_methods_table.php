<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreatePaymentMethodsTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('payment_methods'), function (Blueprint $table) {
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
}
