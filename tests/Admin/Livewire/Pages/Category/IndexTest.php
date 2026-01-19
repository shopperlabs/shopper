<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Category;
use Shopper\Livewire\Pages\Category\Index;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_categories');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render categories index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.category.index')
            ->assertSee(__('shopper::pages/categories.menu'));
    });

    it('can list categories in table', function (): void {
        Category::factory()->count(3)->create();

        Livewire::test(Index::class)
            ->loadTable()
            ->assertCanSeeTableRecords(Category::limit(3)->get());
    });

    it('can filter categories by visibility', function (): void {
        Category::factory()->count(2)->create(['is_enabled' => true]);
        Category::factory()->create(['is_enabled' => false]);

        Livewire::test(Index::class)
            ->filterTable('is_enabled', true)
            ->assertCountTableRecords(2);
    });
})->group('livewire', 'categories');
