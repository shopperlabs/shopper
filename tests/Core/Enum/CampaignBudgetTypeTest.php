<?php

declare(strict_types=1);

use Shopper\Core\Enum\CampaignBudgetType;

uses(Tests\Core\TestCase::class);

describe(CampaignBudgetType::class, function (): void {
    it('reports a spend cap for Spend and SpendAndCount only', function (): void {
        expect(CampaignBudgetType::None->hasSpendCap())->toBeFalse()
            ->and(CampaignBudgetType::Count->hasSpendCap())->toBeFalse()
            ->and(CampaignBudgetType::Spend->hasSpendCap())->toBeTrue()
            ->and(CampaignBudgetType::SpendAndCount->hasSpendCap())->toBeTrue();
    });

    it('reports a count cap for Count and SpendAndCount only', function (): void {
        expect(CampaignBudgetType::None->hasCountCap())->toBeFalse()
            ->and(CampaignBudgetType::Spend->hasCountCap())->toBeFalse()
            ->and(CampaignBudgetType::Count->hasCountCap())->toBeTrue()
            ->and(CampaignBudgetType::SpendAndCount->hasCountCap())->toBeTrue();
    });
})->group('enums', 'campaigns');
