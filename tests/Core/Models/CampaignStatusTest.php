<?php

declare(strict_types=1);

use Carbon\Carbon;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Enum\CampaignStatus;
use Shopper\Core\Models\Campaign;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    Carbon::setTestNow('2026-01-15 12:00:00');
});

afterEach(function (): void {
    Carbon::setTestNow();
});

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

    it('returns BudgetExhausted when only the count cap is reached on a spend-and-count campaign', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(amount: 100_000)
            ->create([
                'budget_type' => CampaignBudgetType::SpendAndCount->value,
                'budget_count' => 5,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDay(),
                'spent_amount' => 10_000,
                'used_count' => 5,
            ]);

        expect($campaign->status)->toBe(CampaignStatus::BudgetExhausted);
    });

    it('stays Active for a None budget even when a stale budget_amount is exceeded', function (): void {
        $campaign = Campaign::factory()->create([
            'budget_type' => CampaignBudgetType::None->value,
            'budget_amount' => 1_000,
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
