<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Discount;
use Shopper\Jobs\AttachedDiscountToCustomers;
use Shopper\Jobs\AttachedDiscountToProducts;
use Shopper\Livewire\Pages\Discount\Create;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('discounts.create', 'discounts.edit');
    $this->actingAs($this->user);

    $this->products = Product::factory()->count(3)->publish()->create();

    Queue::fake();
});

describe(Create::class, function (): void {
    it('creates a new discount and redirects to the edit page', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'code' => 'SUMMER23',
                'is_active' => true,
                'type' => DiscountType::FixedAmount,
                'value' => 1000,
                'apply_to' => DiscountApplyTo::Products,
                'products' => $this->products->pluck('id')->toArray(),
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
                'start_at' => now(),
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        Queue::assertPushed(AttachedDiscountToProducts::class);
        Queue::assertPushed(AttachedDiscountToCustomers::class);

        expect(Discount::query()->count())->toBe(1);
    });

    it('rejects a percentage value above 100', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'code' => 'OVER',
                'is_active' => true,
                'type' => DiscountType::Percentage,
                'value' => 150,
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
                'start_at' => now(),
            ])
            ->call('save')
            ->assertHasFormErrors(['value']);

        expect(Discount::query()->count())->toBe(0);
    });

    it('rejects a negative value', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'code' => 'NEG',
                'is_active' => true,
                'type' => DiscountType::FixedAmount,
                'value' => -50,
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
                'start_at' => now(),
            ])
            ->call('save')
            ->assertHasFormErrors(['value']);
    });

    it('rejects a start date in the past', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'code' => 'PAST',
                'is_active' => false,
                'type' => DiscountType::Percentage,
                'value' => 10,
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
                'start_at' => now()->subDays(10),
            ])
            ->call('save')
            ->assertHasFormErrors(['start_at']);
    });

    it('exposes a live summary that reflects the form state', function (): void {
        $component = Livewire::test(Create::class)
            ->fillForm([
                'code' => 'PROMO',
                'type' => DiscountType::Percentage,
                'value' => 15,
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
                'start_at' => now(),
            ]);

        $summary = $component->instance()->summary;

        expect($summary['code'])->toBe('PROMO')
            ->and($summary['type'])->toBe(DiscountType::Percentage)
            ->and($summary['type_label'])->toBe('15% off');
    });

    it('refuses access without the discounts.create permission', function (): void {
        $stranger = User::factory()->create();
        $this->actingAs($stranger);

        Livewire::test(Create::class)
            ->assertForbidden();
    });

    it('clears products and switches apply_to when confirm action runs', function (): void {
        $component = Livewire::test(Create::class)
            ->fillForm([
                'apply_to' => DiscountApplyTo::Products,
                'eligibility' => DiscountEligibility::Everyone,
                'min_required' => DiscountRequirement::None,
                'type' => DiscountType::Percentage,
                'value' => 10,
                'start_at' => now(),
            ])
            ->call('addProductsToDiscount', [1, 2]);

        $component->callAction('confirmClearProducts', arguments: ['target' => DiscountApplyTo::Order->value]);

        expect($component->get('data.products'))->toBe([])
            ->and($component->get('data.apply_to'))->toBe(DiscountApplyTo::Order->value);
    });

    it('clears customers and switches eligibility when confirm action runs', function (): void {
        $component = Livewire::test(Create::class)
            ->fillForm([
                'apply_to' => DiscountApplyTo::Order,
                'eligibility' => DiscountEligibility::Customers,
                'min_required' => DiscountRequirement::None,
                'type' => DiscountType::Percentage,
                'value' => 10,
                'start_at' => now(),
            ])
            ->call('addCustomersToDiscount', [1, 2]);

        $component->callAction('confirmClearCustomers', arguments: ['target' => DiscountEligibility::Everyone->value]);

        expect($component->get('data.customers'))->toBe([])
            ->and($component->get('data.eligibility'))->toBe(DiscountEligibility::Everyone->value);
    });
})->group('livewire', 'pages', 'discounts');
