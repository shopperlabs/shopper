<?php

declare(strict_types=1);

use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Exceptions\CampaignBudgetExceededException;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CampaignBudgetMovement;

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
})->group('actions', 'campaigns');
