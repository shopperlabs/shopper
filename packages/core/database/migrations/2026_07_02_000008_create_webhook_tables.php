<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return config('shopper.webhooks.database.connection') ?? parent::getConnection();
    }

    public function up(): void
    {
        Schema::create($this->getTableName('webhook_subscriptions'), static function (Blueprint $table): void {
            $table->id();
            $table->string('url');
            $table->json('events');
            $table->text('secret');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });

        Schema::create($this->getTableName('webhook_events'), static function (Blueprint $table): void {
            $table->id();
            $table->string('public_id', 26)->nullable()->unique();
            $table->string('name');
            $table->string('resource_type')->nullable();
            $table->string('resource_id')->nullable();
            $table->longText('payload');
            $table->timestamps();

            $table->index(['name', 'created_at']);
        });

        Schema::create($this->getTableName('webhook_deliveries'), function (Blueprint $table): void {
            $table->id();
            $table->string('public_id', 26)->nullable()->unique();
            $table->foreignId('webhook_event_id')
                ->constrained($this->getTableName('webhook_events'))
                ->cascadeOnDelete();
            $table->foreignId('webhook_subscription_id')
                ->constrained($this->getTableName('webhook_subscriptions'))
                ->cascadeOnDelete();
            $table->unsignedInteger('attempt_number')->default(1);
            $table->string('status');
            $table->unsignedSmallInteger('response_code')->nullable();
            $table->text('response_body')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('webhook_event_id');
            $table->index(['webhook_subscription_id', 'status', 'id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('webhook_deliveries'));
        Schema::dropIfExists($this->getTableName('webhook_events'));
        Schema::dropIfExists($this->getTableName('webhook_subscriptions'));
    }
};
