<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Enum\CampaignBudgetDirection;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('campaign_budget_movements'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $this->addForeignKey($table, 'campaign_id', $this->getTableName('campaigns'), false);
            $this->addForeignKey($table, 'order_id', $this->getTableName('orders'));

            $table->string('direction')->default(CampaignBudgetDirection::Reserve->value);
            $table->bigInteger('amount');
            $table->unsignedBigInteger('balance_after');
            $table->string('actor')->nullable();

            $table->unique(['campaign_id', 'order_id', 'direction'], 'campaign_movements_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('campaign_budget_movements'));
    }
};
