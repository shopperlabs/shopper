<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\SlideOvers\BrandForm;
use Tests\Core\Stubs\Brand;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.brand', Brand::class);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_brands', 'edit_brands');
    $this->actingAs($this->user);
});

describe(BrandForm::class, function (): void {
    it('can validate required fields on brand form', function (): void {
        Livewire::test(BrandForm::class)
            ->assertFormExists()
            ->fillForm()
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('can create brand', function (): void {
        Livewire::test(BrandForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'Nike',
            ])
            ->call('save')
            ->assertRedirectToRoute('shopper.brands.index');
    });

    it('will generate a slug when brand slug already exists', function (): void {
        Brand::factory()->create(['name' => 'Nike Old', 'slug' => 'nike']);

        Livewire::test(BrandForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'Nike',
            ])
            ->call('save')
            ->assertRedirectToRoute('shopper.brands.index');

        expect(Brand::resolvedQuery()->count())
            ->toBe(2)
            ->and(Brand::resolvedQuery()->find(2)?->slug)
            ->toBe('nike-1');
    });
})->group('livewire', 'slideovers', 'brands');
