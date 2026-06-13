<?php

declare(strict_types=1);

use Shopper\Core\Enum\CampaignStatus;
use Shopper\Core\Models\Campaign;

uses(Tests\Core\TestCase::class);

describe('Campaign status accessor', function (): void {
    it('returns Draft when the campaign is not persisted', function (): void {
        $campaign = Campaign::factory()->make();

        expect($campaign->status)->toBe(CampaignStatus::Draft);
    });

    it('returns Disabled when is_active is false', function (): void {
        $campaign = Campaign::factory()->create([
            'is_active' => false,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        expect($campaign->status)->toBe(CampaignStatus::Disabled);
    });

    it('returns Expired when ends_at is in the past', function (): void {
        $campaign = Campaign::factory()->create([
            'is_active' => true,
            'starts_at' => now()->subDays(10),
            'ends_at' => now()->subDay(),
        ]);

        expect($campaign->status)->toBe(CampaignStatus::Expired);
    });

    it('returns BudgetExhausted when the spend cap is reached', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create([
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDay(),
                'spent_amount' => 100_000,
            ]);

        expect($campaign->status)->toBe(CampaignStatus::BudgetExhausted);
    });

    it('returns BudgetExhausted when the usage cap is reached', function (): void {
        $campaign = Campaign::factory()
            ->withCountBudget(count: 50)
            ->create([
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDay(),
                'used_count' => 50,
            ]);

        expect($campaign->status)->toBe(CampaignStatus::BudgetExhausted);
    });

    it('returns Scheduled when starts_at is in the future', function (): void {
        $campaign = Campaign::factory()->create([
            'is_active' => true,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDays(10),
        ]);

        expect($campaign->status)->toBe(CampaignStatus::Scheduled);
    });

    it('returns Active within the window without any cap reached', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create([
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDay(),
                'spent_amount' => 5_000,
            ]);

        expect($campaign->status)->toBe(CampaignStatus::Active);
    });

    it('prioritises Expired over BudgetExhausted', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create([
                'is_active' => true,
                'starts_at' => now()->subDays(10),
                'ends_at' => now()->subDay(),
                'spent_amount' => 100_000,
            ]);

        expect($campaign->status)->toBe(CampaignStatus::Expired);
    });
})->group('models', 'campaigns');
