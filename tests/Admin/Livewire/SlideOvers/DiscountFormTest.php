<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\Product;
use Shopper\Jobs\AttachedDiscountToCustomers;
use Shopper\Jobs\AttachedDiscountToProducts;
use Shopper\Livewire\SlideOvers\DiscountForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('discounts.create', 'discounts.edit');
    $this->actingAs($this->user);

    Queue::fake();
});

describe(DiscountForm::class, function (): void {
    it('renders the slide-over for creation when no discount id is given', function (): void {
        Livewire::test(DiscountForm::class)
            ->assertSuccessful()
            ->assertSet('discount.id', null);
    });

    it('hydrates the form with the discount data when an id is given', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'EDIT_ME',
            'type' => DiscountType::Percentage,
        ]);

        Livewire::test(DiscountForm::class, ['discountId' => $discount->id])
            ->assertSuccessful()
            ->assertSet('discount.id', $discount->id)
            ->assertSet('title', 'EDIT_ME');
    });

    it('hydrates products and customers from existing items', function (): void {
        $products = Product::factory()->count(2)->create();
        $customers = User::factory()->count(2)->create()->each(fn ($u) => $u->assignRole(config('shopper.admin.roles.user')));

        $discount = Discount::factory()->create([
            'apply_to' => DiscountApplyTo::Products->value,
            'eligibility' => DiscountEligibility::Customers->value,
        ]);

        foreach ($products as $product) {
            DiscountDetail::query()->create([
                'discount_id' => $discount->id,
                'discountable_type' => $product->getMorphClass(),
                'discountable_id' => $product->id,
                'condition' => DiscountCondition::ApplyTo,
            ]);
        }

        foreach ($customers as $customer) {
            DiscountDetail::query()->create([
                'discount_id' => $discount->id,
                'discountable_type' => $customer->getMorphClass(),
                'discountable_id' => $customer->id,
                'condition' => DiscountCondition::Eligibility,
            ]);
        }

        $component = Livewire::test(DiscountForm::class, ['discountId' => $discount->id]);

        expect($component->get('data.products'))->toHaveCount(2)
            ->and($component->get('data.customers'))->toHaveCount(2);
    });

    it('stores a new discount and redirects to the index', function (): void {
        Livewire::test(DiscountForm::class)
            ->fillForm([
                'code' => 'NEW_CODE',
                'type' => DiscountType::Percentage,
                'value' => 10,
                'apply_to' => DiscountApplyTo::Order,
                'eligibility' => DiscountEligibility::Everyone,
                'min_required' => DiscountRequirement::None,
                'is_active' => true,
                'start_at' => now()->addDay(),
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertRedirect(route('shopper.discounts.index'));

        expect(Discount::query()->where('code', 'NEW_CODE')->exists())->toBeTrue();
    });

    it('updates an existing discount and redirects to the index', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 15,
        ]);

        Livewire::test(DiscountForm::class, ['discountId' => $discount->id])
            ->fillForm([
                'code' => $code = 'EDITED_CODE',
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertRedirect(route('shopper.discounts.index'));

        Queue::assertPushed(AttachedDiscountToProducts::class);
        Queue::assertPushed(AttachedDiscountToCustomers::class);

        $discount->refresh();
        expect($discount->code)->toBe($code);
    });

    it('refuses access without discount permissions', function (): void {
        $stranger = User::factory()->create();
        $this->actingAs($stranger);

        Livewire::test(DiscountForm::class)
            ->assertForbidden();
    });
})->group('livewire', 'slideovers', 'discounts');
