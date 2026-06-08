<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Exceptions\DiscountZoneFrozenException;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Zone;
use Shopper\Jobs\AttachedDiscountToCustomers;
use Shopper\Jobs\AttachedDiscountToProducts;
use Shopper\Livewire\Pages\Discount\Edit;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('discounts.create', 'discounts.edit', 'discounts.delete');
    $this->actingAs($this->user);

    $this->customers = User::factory()->count(3)->create()->each(function ($user): void {
        $user->assignRole(config('shopper.admin.roles.user'));
    });

    Queue::fake();
});

describe(Edit::class, function (): void {
    it('updates an existing discount', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 15,
        ]);

        Livewire::test(Edit::class, ['record' => $discount->id])
            ->fillForm([
                'code' => $code = 'LAURE_MONNEY_2025',
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Customers,
                'customers' => $this->customers->pluck('id')->toArray(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        Queue::assertPushed(AttachedDiscountToProducts::class);
        Queue::assertPushed(AttachedDiscountToCustomers::class);

        $discount->refresh();
        expect($discount->code)->toBe($code);
    });

    it('declares the discount property as Locked', function (): void {
        $reflection = new ReflectionClass(Edit::class);
        $property = $reflection->getProperty('discount');

        $names = array_map(
            fn (ReflectionAttribute $attr): string => $attr->getName(),
            $property->getAttributes(),
        );

        expect($names)->toContain(\Livewire\Attributes\Locked::class);
    });

    it('throws when changing the zone of a fixed-amount discount that has been used', function (): void {
        $eur = Currency::query()->firstOrCreate(['code' => 'EUR'], ['name' => 'Euro', 'symbol' => '€']);
        $usd = Currency::query()->firstOrCreate(['code' => 'USD'], ['name' => 'Dollar', 'symbol' => '$']);

        $zoneEu = Zone::query()->create(['name' => 'EU', 'code' => 'EU', 'currency_id' => $eur->id]);
        $zoneUs = Zone::query()->create(['name' => 'US', 'code' => 'US', 'currency_id' => $usd->id]);

        $discount = Discount::factory()->create([
            'type' => DiscountType::FixedAmount,
            'zone_id' => $zoneEu->id,
            'total_use' => 5,
        ]);

        expect(fn () => $discount->update(['zone_id' => $zoneUs->id]))
            ->toThrow(DiscountZoneFrozenException::class);
    });

    it('throws ModelNotFoundException for a non existing discount', function (): void {
        Livewire::test(Edit::class, ['record' => 999_999]);
    })->throws(Illuminate\Database\Eloquent\ModelNotFoundException::class);

    it('refuses access without the discounts.edit permission', function (): void {
        $discount = Discount::factory()->create();

        $stranger = User::factory()->create();
        $this->actingAs($stranger);

        Livewire::test(Edit::class, ['record' => $discount->id])
            ->assertForbidden();
    });
})->group('livewire', 'pages', 'discounts');
