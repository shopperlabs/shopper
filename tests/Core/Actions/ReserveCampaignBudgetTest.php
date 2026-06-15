<?php

declare(strict_types=1);

use Illuminate\Database\UniqueConstraintViolationException;
use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Exceptions\CampaignBudgetExceededException;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CampaignBudgetMovement;
use Shopper\Core\Models\Order;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->action = resolve(ReserveCampaignBudget::class);
});

describe(ReserveCampaignBudget::class, function (): void {
    it('draws down the spend budget and records a movement', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();

        $this->action->execute($campaign, spend: 30_000);

        $campaign->refresh();

        expect($campaign->spent_amount)->toBe(30_000)
            ->and($campaign->used_count)->toBe(1);

        $movement = CampaignBudgetMovement::query()->firstOrFail();

        expect($movement->amount)->toBe(30_000)
            ->and($movement->balance_after)->toBe(30_000)
            ->and($movement->campaign_id)->toBe($campaign->id);
    });

    it('allows a redemption that exactly meets the spend cap', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create(['spent_amount' => 70_000]);

        $this->action->execute($campaign, spend: 30_000);

        expect($campaign->fresh()->spent_amount)->toBe(100_000);
    });

    it('rejects a redemption that would exceed the spend cap and records nothing', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create(['spent_amount' => 90_000, 'used_count' => 3]);

        expect(fn () => $this->action->execute($campaign, spend: 20_000))
            ->toThrow(CampaignBudgetExceededException::class);

        $campaign->refresh();

        expect($campaign->spent_amount)->toBe(90_000)
            ->and($campaign->used_count)->toBe(3)
            ->and(CampaignBudgetMovement::query()->count())->toBe(0);
    });

    it('rejects a redemption once the usage cap is reached', function (): void {
        $campaign = Campaign::factory()->withCountBudget(count: 5)->create(['used_count' => 5]);

        expect(fn () => $this->action->execute($campaign, spend: 1_000))
            ->toThrow(CampaignBudgetExceededException::class);
    });

    it('tracks spend and usage for a campaign without any cap', function (): void {
        $campaign = Campaign::factory()->create();

        $this->action->execute($campaign, spend: 12_345);

        $campaign->refresh();

        expect($campaign->spent_amount)->toBe(12_345)
            ->and($campaign->used_count)->toBe(1);
    });

    it('keeps sequential reservations from overshooting the cap', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();

        $this->action->execute($campaign, spend: 60_000);

        expect(fn () => $this->action->execute($campaign->fresh(), spend: 60_000))
            ->toThrow(CampaignBudgetExceededException::class);

        expect($campaign->fresh()->spent_amount)->toBe(60_000);
    });

    it('rejects when the spend cap is hit on a spend-and-count campaign even if the count cap is open', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 1_000)
            ->create([
                'budget_type' => CampaignBudgetType::SpendAndCount->value,
                'budget_count' => 10,
                'spent_amount' => 1_000,
                'used_count' => 2,
            ]);

        expect(fn () => $this->action->execute($campaign, spend: 1))
            ->toThrow(CampaignBudgetExceededException::class);
    });

    it('rejects when the count cap is hit on a spend-and-count campaign even if spend remains', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create([
                'budget_type' => CampaignBudgetType::SpendAndCount->value,
                'budget_count' => 5,
                'spent_amount' => 10_000,
                'used_count' => 5,
            ]);

        expect(fn () => $this->action->execute($campaign, spend: 1_000))
            ->toThrow(CampaignBudgetExceededException::class);
    });

    it('never blocks when budget_type is None even if a stale budget_amount lingers', function (): void {
        $campaign = Campaign::factory()->create([
            'budget_type' => CampaignBudgetType::None->value,
            'budget_amount' => 1_000,
            'spent_amount' => 5_000,
        ]);

        $this->action->execute($campaign, spend: 9_999);

        expect($campaign->fresh()->spent_amount)->toBe(14_999);
    });

    it('does not double count a duplicate reservation for the same order', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
        $order = Order::factory()->create();

        $this->action->execute($campaign, spend: 1_000, orderId: $order->id);

        expect(fn () => $this->action->execute($campaign->fresh(), spend: 1_000, orderId: $order->id))
            ->toThrow(UniqueConstraintViolationException::class);

        // The transaction rolled the increment back with the failed movement:
        // the budget was charged exactly once for order 99.
        expect($campaign->fresh()->spent_amount)->toBe(1_000)
            ->and(CampaignBudgetMovement::query()->where('campaign_id', $campaign->id)->count())->toBe(1);
    });

    it('records a cumulative balance and the actor across movements', function (): void {
        $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
        $first = Order::factory()->create();
        $second = Order::factory()->create();

        $this->action->execute($campaign, spend: 30_000, orderId: $first->id, actor: 'checkout');
        $this->action->execute($campaign->fresh(), spend: 20_000, orderId: $second->id);

        $movements = CampaignBudgetMovement::query()
            ->where('campaign_id', $campaign->id)
            ->orderBy('id')
            ->get();

        expect($movements->first()->balance_after)->toBe(30_000)
            ->and($movements->first()->actor)->toBe('checkout')
            ->and($movements->last()->balance_after)->toBe(50_000);
    });
})->group('actions', 'campaigns');
