<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Contracts\Brand as BrandContract;
use Shopper\Livewire\SlideOvers\BrandForm;
use Tests\Core\Stubs\Brand;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_brands', 'edit_brands');
    $this->actingAs($this->user);
});

describe(BrandForm::class, function (): void {
    it('can validate required fields on brand form', function (): void {
        Livewire::test(BrandForm::class)
            ->assertSchemaExists('form')
            ->fillForm()
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('can create brand', function (): void {
        Livewire::test(BrandForm::class)
            ->assertSchemaExists('form')
            ->fillForm([
                'name' => 'Nike',
            ])
            ->call('save')
            ->assertRedirectToRoute('shopper.brands.index');
    });

    it('will generate a slug when brand slug already exists', function (): void {
        Brand::factory()->create(['name' => 'Nike Old', 'slug' => 'nike']);

        Livewire::test(BrandForm::class)
            ->assertSchemaExists('form')
            ->fillForm([
                'name' => 'Nike',
            ])
            ->call('save')
            ->assertSuccessful();

        /** @var Illuminate\Database\Eloquent\Builder<BrandContract> $query */
        $query = resolve(BrandContract::class)::query();

        expect($query->count())
            ->toBe(2)
            ->and($query->latest()->first()?->slug)
            ->toBe('nike-1');
    });

    it('dispatches unauthorized notification when user lacks `add_brands` permission', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(BrandForm::class)
            ->fillForm(['name' => 'Nike'])
            ->call('save')
            ->assertNotified(__('shopper::notifications.unauthorized.title'));
    });

    it('dispatches unauthorized notification when user lacks `edit_brands` permission', function (): void {
        $brand = Brand::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(BrandForm::class, ['brand' => $brand])
            ->fillForm(['name' => 'Nike Updated'])
            ->call('save')
            ->assertNotified(__('shopper::notifications.unauthorized.title'));
    });
})->group('livewire', 'slideovers', 'brands');
