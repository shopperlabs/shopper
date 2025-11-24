<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Jobs\AttachedDiscountToCustomers;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

describe(AttachedDiscountToCustomers::class, function (): void {
    it('can be dispatched', function (): void {
        Bus::fake();

        $discount = Discount::factory()->create();
        $customers = User::factory()->count(3)->create();

        AttachedDiscountToCustomers::dispatch(
            DiscountEligibility::Customers(),
            $customers->pluck('id')->toArray(),
            $discount
        );

        Bus::assertDispatched(AttachedDiscountToCustomers::class);
    });

    it('attaches discount to selected customers', function (): void {
        $discount = Discount::factory()->create();
        $customers = User::factory()->count(3)->create();

        $job = new AttachedDiscountToCustomers(
            eligibility: DiscountEligibility::Customers(),
            customersIds: $customers->pluck('id')->toArray(),
            discount: $discount
        );

        $job->handle();

        expect($discount->items()->where('condition', 'eligibility')->count())->toBe(3);

        foreach ($customers as $customer) {
            $detail = DiscountDetail::query()
                ->where('discount_id', $discount->id)
                ->where('discountable_id', $customer->id)
                ->where('condition', 'eligibility')
                ->first();

            expect($detail)->not->toBeNull()
                ->and($detail->discountable_type)->toBe(config('auth.providers.users.model', User::class));
        }
    });

    it('removes unselected customers from discount', function (): void {
        $discount = Discount::factory()->create();
        $existingCustomers = User::factory()->count(3)->create();

        foreach ($existingCustomers as $customer) {
            DiscountDetail::factory()->create([
                'discount_id' => $discount->id,
                'discountable_id' => $customer->id,
                'discountable_type' => User::class,
                'condition' => 'eligibility',
            ]);
        }

        $newCustomers = User::factory()->count(2)->create();

        $job = new AttachedDiscountToCustomers(
            eligibility: DiscountEligibility::Customers(),
            customersIds: $newCustomers->pluck('id')->toArray(),
            discount: $discount
        );

        $job->handle();

        expect($discount->items()->where('condition', 'eligibility')->count())->toBe(2);

        foreach ($existingCustomers as $customer) {
            expect(
                $discount->items()
                    ->where('discountable_id', $customer->id)
                    ->where('condition', 'eligibility')
                    ->exists()
            )->toBeFalse();
        }

        foreach ($newCustomers as $customer) {
            expect(
                $discount->items()
                    ->where('discountable_id', $customer->id)
                    ->where('condition', 'eligibility')
                    ->exists()
            )->toBeTrue();
        }
    });

    it('removes all customers when eligibility is not customers', function (): void {
        $discount = Discount::factory()->create();

        DiscountDetail::factory()->count(3)->create([
            'discount_id' => $discount->id,
            'condition' => 'eligibility',
        ]);

        $job = new AttachedDiscountToCustomers(
            eligibility: DiscountEligibility::Everyone(),
            customersIds: [],
            discount: $discount
        );

        $job->handle();

        expect($discount->items()->where('condition', 'eligibility')->count())->toBe(0);
    });

    it('updates existing customer discount associations', function (): void {
        $discount = Discount::factory()->create();
        $customer = User::factory()->create();

        DiscountDetail::factory()->create([
            'discount_id' => $discount->id,
            'discountable_id' => $customer->id,
            'discountable_type' => User::class,
            'condition' => 'eligibility',
        ]);

        $initialCount = DiscountDetail::query()
            ->where('discount_id', $discount->id)
            ->where('discountable_id', $customer->id)
            ->count();

        $job = new AttachedDiscountToCustomers(
            eligibility: DiscountEligibility::Customers(),
            customersIds: [$customer->id],
            discount: $discount
        );

        $job->handle();

        $finalCount = DiscountDetail::query()
            ->where('discount_id', $discount->id)
            ->where('discountable_id', $customer->id)
            ->count();

        expect($finalCount)->toBe($initialCount)
            ->and($finalCount)->toBe(1);
    });
})->group('jobs', 'discount');
