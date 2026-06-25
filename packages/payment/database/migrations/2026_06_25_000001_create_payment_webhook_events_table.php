<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('payment_webhook_events'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('driver');
            $table->string('event_id');
            $table->string('type')->nullable();
            $table->jsonb('payload')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->unique(['driver', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('payment_webhook_events'));
    }
};
