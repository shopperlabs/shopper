<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('campaigns'), function (Blueprint $table): void {
            $this->addCommonFields($table);
            $table->ulid('public_id')->nullable()->unique();

            $table->boolean('is_active')->default(true);
            $table->string('name');
            $table->string('currency_code');
            $table->string('budget_type')->default(CampaignBudgetType::None->value);
            $table->unsignedBigInteger('budget_amount')->nullable();
            $table->unsignedInteger('budget_count')->nullable();
            $table->unsignedBigInteger('spent_amount')->default(0);
            $table->unsignedInteger('used_count')->default(0);
            $table->jsonb('metadata')->nullable();

            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();

            $table->index(['is_active', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('campaigns'));
    }
};
