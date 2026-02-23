<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('order_shipping'), static function (Blueprint $table): void {
            $table->string('status', 32)->nullable()->after('id');
            $table->dateTimeTz('shipped_at')->nullable()->change();
        });

        Schema::create($this->getTableName('order_shipping_events'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $this->addForeignKey($table, 'order_shipping_id', $this->getTableName('order_shipping'), false);

            $table->string('status', 32);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('latitude', 11, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTimeTz('occurred_at');
            $table->jsonb('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('order_shipping_events'));

        Schema::table($this->getTableName('order_shipping'), static function (Blueprint $table): void {
            $table->dropColumn('status');
        });
    }
};
