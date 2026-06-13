<?php

declare(strict_types=1);

use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Contracts\Campaign as CampaignContract;
use Shopper\Core\Models\Discount;

uses(Tests\Core\TestCase::class);

describe(Campaign::class, function (): void {
    it('resolves the configured class through the container', function (): void {
        expect(resolve(CampaignContract::class))->toBeInstanceOf(Campaign::class);
    });

    it('uses the prefixed campaigns table', function (): void {
        expect((new Campaign)->getTable())->toBe(shopper_table('campaigns'));
    });

    it('casts budget columns to their native types', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 250_000)
            ->create(['budget_count' => 30]);

        expect($campaign)
            ->budget_type->toBe(CampaignBudgetType::Spend)
            ->budget_amount->toBeInt()
            ->spent_amount->toBeInt()
            ->used_count->toBeInt();
    });

    it('generates a public id on creation', function (): void {
        $campaign = Campaign::factory()->create();

        expect($campaign->public_id)->not->toBeNull();
    });

    it('owns many discount codes', function (): void {
        $campaign = Campaign::factory()->create();
        Discount::factory()->count(3)->create(['campaign_id' => $campaign->id]);

        expect($campaign->discounts)->toHaveCount(3)
            ->and($campaign->discounts->first())->toBeInstanceOf(Discount::class);
    });

    it('keeps standalone discounts without a campaign', function (): void {
        $discount = Discount::factory()->create(['campaign_id' => null]);

        expect($discount->campaign_id)->toBeNull()
            ->and($discount->campaign)->toBeNull();
    });

    it('reports a reached budget once the spend cap is met', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create(['spent_amount' => 100_000]);

        expect($campaign->hasReachedBudget())->toBeTrue();
    });

    it('does not report a reached budget below the spend cap', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create(['spent_amount' => 99_999]);

        expect($campaign->hasReachedBudget())->toBeFalse();
    });
})->group('models', 'campaigns');
