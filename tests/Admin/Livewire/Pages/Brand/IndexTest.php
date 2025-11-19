<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\User;
use Shopper\Livewire\Pages\Brand\Index;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_brands');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render brands index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.brand.index')
            ->assertSee(__('shopper::pages/brands.menu'));
    });

    it('can list brands in table', function (): void {
        Brand::factory()->count(3)->create();

        Livewire::test(Index::class)
            ->assertCanSeeTableRecords(Brand::limit(3)->get());
    });

    it('can filter brands by visibility', function (): void {
        Brand::factory()->count(2)->create(['is_enabled' => true]);
        Brand::factory()->create(['is_enabled' => false]);

        Livewire::test(Index::class)
            ->filterTable('is_enabled', true)
            ->assertCountTableRecords(2);
    });
})->group('livewire', 'brands');
