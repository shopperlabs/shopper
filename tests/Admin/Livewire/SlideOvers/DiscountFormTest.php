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
use Shopper\Livewire\SlideOvers\DiscountForm;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_discounts', 'edit_discounts');
    $this->actingAs($this->user);

    $this->products = Product::factory()->count(3)->publish()->create();
    $this->users = User::factory()->count(3)->create();

    Queue::fake();
});

describe(DiscountForm::class, function (): void {
    it('creates a new discount', function (): void {
        Livewire::test(DiscountForm::class)
            ->fillForm([
                'code' => 'SUMMER23',
                'is_active' => true,
                'type' => DiscountType::FixedAmount(),
                'value' => 1000,
                'apply_to' => DiscountApplyTo::Products(),
                'products' => $this->products->pluck('id')->toArray(),
                'min_required' => DiscountRequirement::None(),
                'eligibility' => DiscountEligibility::Everyone(),
                'start_at' => now(),
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        Queue::assertPushed(AttachedDiscountToProducts::class);
        Queue::assertPushed(AttachedDiscountToCustomers::class);

        expect(Discount::query()->count())->toBe(1);

        Queue::assertCount(2);
    });

    it('should not create a discount with a date in the past', function (): void {
        Livewire::test(DiscountForm::class)
            ->fillForm([
                'code' => 'SUMMER23',
                'is_active' => false,
                'type' => DiscountType::Percentage(),
                'value' => 10,
                'apply_to' => DiscountApplyTo::Order(),
                'min_required' => DiscountRequirement::None(),
                'eligibility' => DiscountEligibility::Everyone(),
                'start_at' => now()->subDays(10),
            ])
            ->call('store')
            ->assertHasFormErrors(['start_at']);
    });

    it('can update a discount', function (): void {
        $discount = Discount::factory()->create();

        Livewire::test(DiscountForm::class, ['discountId' => $discount->id])
            ->fillForm([
                'code' => $code = 'LAURE_MONNEY_2025',
                'apply_to' => DiscountApplyTo::Order(),
                'min_required' => DiscountRequirement::None(),
                'eligibility' => DiscountEligibility::Customers(),
                'customers' => $this->users->pluck('id')->toArray(),
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        Queue::assertPushed(AttachedDiscountToProducts::class);
        Queue::assertPushed(AttachedDiscountToCustomers::class);

        $discount->refresh();

        expect($discount)->toBeInstanceOf(Discount::class)
            ->and($discount->code)
            ->toBe($code);

        Queue::assertCount(2);
    });
})->group('livewire', 'slideovers', 'discounts');
