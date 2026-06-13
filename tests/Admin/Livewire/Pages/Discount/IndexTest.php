<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountStatus;
use Shopper\Core\Models\Discount;
use Shopper\Livewire\Pages\Discount\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('discounts.browse');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render discounts index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.discounts.index')
            ->assertSee(__('shopper::pages/discounts.menu'));
    });

    it('can list discounts in table', function (): void {
        Discount::factory()->count(3)->create();

        Livewire::test(Index::class)
            ->loadTable()
            ->assertCanSeeTableRecords(Discount::limit(3)->get());
    });

    it('can filter discounts by status', function (): void {
        Discount::factory()->count(2)->create(['is_active' => true]);
        Discount::factory()->create(['is_active' => false]);

        Livewire::test(Index::class)
            ->filterTable('is_active', true)
            ->assertCountTableRecords(2);
    });

    it('shows the derived `Expired` status for an enabled discount past its end date', function (): void {
        $discount = Discount::factory()->create([
            'is_active' => true,
            'apply_to' => DiscountApplyTo::Order(),
            'start_at' => now()->subMonth(),
            'end_at' => now()->subDay(),
        ]);

        Livewire::test(Index::class)
            ->loadTable()
            ->assertTableColumnStateSet('status', DiscountStatus::Expired, $discount);
    });

    it('shows the derived `LimitReached` status when usage has hit its limit', function (): void {
        $discount = Discount::factory()->create([
            'is_active' => true,
            'apply_to' => DiscountApplyTo::Order(),
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
            'usage_limit' => 100,
            'total_use' => 100,
        ]);

        Livewire::test(Index::class)
            ->loadTable()
            ->assertTableColumnStateSet('status', DiscountStatus::LimitReached, $discount);
    });
})->group('livewire', 'discounts');
