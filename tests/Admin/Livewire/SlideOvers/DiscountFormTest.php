<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Discount;
use Shopper\Livewire\SlideOvers\DiscountForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('discounts.create', 'discounts.edit');
    $this->actingAs($this->user);
});

describe(DiscountForm::class, function (): void {
    it('redirects to the create route on mount when no discount id is given', function (): void {
        Livewire::test(DiscountForm::class)
            ->assertRedirect(route('shopper.discounts.create'));
    });

    it('redirects to the edit route on mount when a discount id is given', function (): void {
        $discount = Discount::factory()->create();

        Livewire::test(DiscountForm::class, ['discountId' => $discount->id])
            ->assertRedirect(route('shopper.discounts.edit', $discount));
    });
})->group('livewire', 'slideovers', 'discounts');
