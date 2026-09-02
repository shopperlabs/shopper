<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('order_shipping_events'), function (Blueprint $table): void {
            $table->string('external_id')->nullable()->after('status');
            $table->string('source', 16)->default('manual')->after('external_id');
            $table->unsignedBigInteger('causer_id')->nullable()->after('source');
            $table->unique(['order_shipping_id', 'external_id'], $this->uniqueIndexName());
        });

        Schema::table($this->getTableName('order_shipping'), static function (Blueprint $table): void {
            $table->index('tracking_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table($this->getTableName('order_shipping'), static function (Blueprint $table): void {
            $table->dropIndex(['status']);
            $table->dropIndex(['tracking_number']);
        });

        Schema::table($this->getTableName('order_shipping_events'), function (Blueprint $table): void {
            $table->dropUnique($this->uniqueIndexName());
            $table->dropColumn(['external_id', 'source', 'causer_id']);
        });
    }

    private function uniqueIndexName(): string
    {
        return $this->getTableName('order_shipping_events').'_external_id_unique';
    }
};
