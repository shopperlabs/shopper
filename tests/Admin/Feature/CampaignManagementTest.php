<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Models\Campaign;
use Shopper\Livewire\Pages\Campaign\Create;
use Shopper\Livewire\Pages\Campaign\Edit;
use Shopper\Livewire\Pages\Campaign\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('campaigns.browse', 'campaigns.create', 'campaigns.edit', 'campaigns.delete');
    $this->actingAs($this->user);
});

describe('campaign management', function (): void {
    it('renders the index page for an authorized user', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.campaigns.index')
            ->assertSee(__('shopper::pages/campaigns.menu'));
    });

    it('forbids the index page for a user without `campaigns.browse`', function (): void {
        $stranger = User::factory()->create();
        $this->actingAs($stranger);

        Livewire::test(Index::class)
            ->assertForbidden();
    });

    it('creates a spend-capped campaign and stores the amount in cents', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'name' => 'Summer Sale',
                'currency_code' => 'USD',
                'is_active' => true,
                'budget_type' => CampaignBudgetType::Spend->value,
                'budget_amount' => 1000,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $campaign = Campaign::query()->firstOrFail();

        expect(Campaign::query()->count())->toBe(1)
            ->and($campaign->name)->toBe('Summer Sale')
            ->and($campaign->currency_code)->toBe('USD')
            ->and($campaign->budget_type)->toBe(CampaignBudgetType::Spend)
            ->and($campaign->budget_amount)->toBe(100000);
    });

    it('stores no budget caps when the budget type is `None`', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'name' => 'Always On',
                'currency_code' => 'USD',
                'is_active' => true,
                'budget_type' => CampaignBudgetType::None->value,
                'starts_at' => now(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $campaign = Campaign::query()->firstOrFail();

        expect($campaign->budget_type)->toBe(CampaignBudgetType::None)
            ->and($campaign->budget_amount)->toBeNull()
            ->and($campaign->budget_count)->toBeNull();
    });

    it('updates an existing campaign', function (): void {
        $campaign = Campaign::factory()->create([
            'name' => 'Old Name',
            'currency_code' => 'USD',
        ]);

        Livewire::test(Edit::class, ['record' => $campaign->id])
            ->fillForm([
                'name' => 'New Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($campaign->fresh()->name)->toBe('New Name');
    });

    it('rejects a currency that is not enabled in the store settings', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'name' => 'Off-store',
                'currency_code' => 'EUR',
                'is_active' => true,
                'budget_type' => CampaignBudgetType::None->value,
                'starts_at' => now(),
            ])
            ->call('save')
            ->assertHasFormErrors(['currency_code']);

        expect(Campaign::query()->count())->toBe(0);
    });

    it('locks the currency once the campaign has recorded spend', function (): void {
        $campaign = Campaign::factory()
            ->withSpendBudget(100000)
            ->create([
                'currency_code' => 'USD',
                'spent_amount' => 5000,
            ]);

        Livewire::test(Edit::class, ['record' => $campaign->id])
            ->assertFormFieldIsDisabled('currency_code');
    });

    it('locks the currency once the campaign has recorded a use', function (): void {
        $campaign = Campaign::factory()
            ->withCountBudget(10)
            ->create(['currency_code' => 'USD', 'used_count' => 3]);

        Livewire::test(Edit::class, ['record' => $campaign->id])
            ->assertFormFieldIsDisabled('currency_code');
    });

    it('stores only the count cap for the `Count` budget type', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'name' => 'Count Only',
                'currency_code' => 'USD',
                'is_active' => true,
                'budget_type' => CampaignBudgetType::Count->value,
                'budget_count' => 50,
                'starts_at' => now(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $campaign = Campaign::query()->firstOrFail();

        expect($campaign->budget_type)->toBe(CampaignBudgetType::Count)
            ->and($campaign->budget_count)->toBe(50)
            ->and($campaign->budget_amount)->toBeNull();
    });

    it('stores both caps for the `SpendAndCount` budget type', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'name' => 'Both Caps',
                'currency_code' => 'USD',
                'is_active' => true,
                'budget_type' => CampaignBudgetType::SpendAndCount->value,
                'budget_amount' => 500,
                'budget_count' => 20,
                'starts_at' => now(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $campaign = Campaign::query()->firstOrFail();

        expect($campaign->budget_type)->toBe(CampaignBudgetType::SpendAndCount)
            ->and($campaign->budget_amount)->toBe(50000)
            ->and($campaign->budget_count)->toBe(20);
    });

    it('forbids the create page without `campaigns.create`', function (): void {
        $this->actingAs(User::factory()->create());

        Livewire::test(Create::class)->assertForbidden();
    });

    it('forbids the edit page without `campaigns.edit`', function (): void {
        $campaign = Campaign::factory()->create(['currency_code' => 'USD']);

        $this->actingAs(User::factory()->create());

        Livewire::test(Edit::class, ['record' => $campaign->id])->assertForbidden();
    });
})->group('livewire', 'campaigns');
