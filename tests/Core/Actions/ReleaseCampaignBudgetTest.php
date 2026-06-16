<?php

declare(strict_types=1);

use Shopper\Core\Actions\ReleaseCampaignBudget;
use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Enum\CampaignBudgetDirection;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CampaignBudgetMovement;
use Shopper\Core\Models\Order;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->reserve = resolve(ReserveCampaignBudget::class);
    $this->release = resolve(ReleaseCampaignBudget::class);
});

describe(ReleaseCampaignBudget::class, function (): void {
    it('returns the reserved amount and records a release movement', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
        $order = Order::factory()->create();
        $this->reserve->execute($campaign, spend: 30_000, orderId: $order->id);

        $this->release->execute($campaign->fresh(), orderId: $order->id, actor: 'order-refunded');

        $campaign->refresh();

        expect($campaign->spent_amount)->toBe(0)
            ->and($campaign->used_count)->toBe(0);

        $movement = CampaignBudgetMovement::query()
            ->where('campaign_id', $campaign->id)
            ->where('direction', CampaignBudgetDirection::Release->value)
            ->firstOrFail();

        expect($movement->amount)->toBe(-30_000)
            ->and($movement->balance_after)->toBe(0)
            ->and($movement->actor)->toBe('order-refunded');
    });

    it('is idempotent when called twice for the same order', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
        $order = Order::factory()->create();
        $this->reserve->execute($campaign, spend: 30_000, orderId: $order->id);

        $this->release->execute($campaign->fresh(), orderId: $order->id);
        $this->release->execute($campaign->fresh(), orderId: $order->id);

        $campaign->refresh();

        expect($campaign->spent_amount)->toBe(0)
            ->and(CampaignBudgetMovement::query()
                ->where('campaign_id', $campaign->id)
                ->where('direction', CampaignBudgetDirection::Release->value)
                ->count())->toBe(1);
    });

    it('does nothing when the order never reserved budget', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create(['spent_amount' => 5_000]);

        $this->release->execute($campaign, orderId: 999);

        expect($campaign->fresh()->spent_amount)->toBe(5_000)
            ->and(CampaignBudgetMovement::query()->count())->toBe(0);
    });

    it('floors the counters at zero and only releases its own reservation', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
        $first = Order::factory()->create();
        $second = Order::factory()->create();
        $this->reserve->execute($campaign, spend: 20_000, orderId: $first->id);
        $this->reserve->execute($campaign->fresh(), spend: 20_000, orderId: $second->id);

        $this->release->execute($campaign->fresh(), orderId: $first->id);

        // Order 2's reservation is untouched.
        expect($campaign->fresh()->spent_amount)->toBe(20_000)
            ->and($campaign->fresh()->used_count)->toBe(1);
    });
})->group('actions', 'campaigns');
