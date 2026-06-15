<?php

declare(strict_types=1);

use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Listeners\Orders\ReleaseCampaignBudgetListener;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Order;

uses(Tests\Core\TestCase::class);

describe(ReleaseCampaignBudgetListener::class, function (): void {
    it('releases the campaign budget when a campaign-backed order is cancelled', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
        $discount = Discount::factory()->create(['campaign_id' => $campaign->id]);
        $order = Order::factory()->create(['discount_id' => $discount->id]);

        resolve(ReserveCampaignBudget::class)->execute($campaign, spend: 25_000, orderId: $order->id);

        resolve(ReleaseCampaignBudgetListener::class)->handle(new OrderCancelled($order));

        expect($campaign->fresh()->spent_amount)->toBe(0)
            ->and($campaign->fresh()->used_count)->toBe(0);
    });

    it('does nothing for an order without a campaign-backed discount', function (): void {
        $order = Order::factory()->create(['discount_id' => null]);

        resolve(ReleaseCampaignBudgetListener::class)->handle(new OrderCancelled($order));
    })->throwsNoExceptions();

    it('releases the budget when the OrderCancelled event is dispatched', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
        $discount = Discount::factory()->create(['campaign_id' => $campaign->id]);
        $order = Order::factory()->create(['discount_id' => $discount->id]);

        resolve(ReserveCampaignBudget::class)->execute($campaign, spend: 25_000, orderId: $order->id);

        event(new OrderCancelled($order));

        // Proves the listener is wired to the event, not just correct in isolation.
        expect($campaign->fresh()->spent_amount)->toBe(0);
    });
})->group('listeners', 'campaigns');
