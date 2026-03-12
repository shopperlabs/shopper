<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Category;
use Shopper\Livewire\SlideOvers\ReOrderCategories;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_categories');
    $this->actingAs($this->user);
});

describe(ReOrderCategories::class, function (): void {
    it('can render reorder categories slideover', function (): void {
        Livewire::test(ReOrderCategories::class)
            ->assertOk();
    });

    it('loads only parent categories ordered by position', function (): void {
        $parent1 = Category::factory()->create(['parent_id' => null, 'position' => 1]);
        $parent2 = Category::factory()->create(['parent_id' => null, 'position' => 2]);
        Category::factory()->create(['parent_id' => $parent1->id]);

        $component = Livewire::test(ReOrderCategories::class);

        $categories = $component->viewData('categories');

        expect($categories)->toHaveCount(2)
            ->and($categories->first()->id)->toBe($parent1->id)
            ->and($categories->last()->id)->toBe($parent2->id);
    });

    it('loads categories with children', function (): void {
        $parent = Category::factory()->create(['parent_id' => null]);
        Category::factory()->count(2)->create(['parent_id' => $parent->id]);

        $component = Livewire::test(ReOrderCategories::class);

        $categories = $component->viewData('categories');

        expect($categories->first()->children)->toHaveCount(2);
    });

    it('can reorder root categories', function (): void {
        $category1 = Category::factory()->create(['parent_id' => null, 'position' => 1]);
        $category2 = Category::factory()->create(['parent_id' => null, 'position' => 2]);
        $category3 = Category::factory()->create(['parent_id' => null, 'position' => 3]);

        Livewire::test(ReOrderCategories::class)
            ->call('reorder', [
                (string) $category3->id,
                (string) $category1->id,
                (string) $category2->id,
            ]);

        expect($category1->refresh()->position)->toBe(2)
            ->and($category2->refresh()->position)->toBe(3)
            ->and($category3->refresh()->position)->toBe(1);
    });

    it('can move a child to a different parent', function (): void {
        $parent1 = Category::factory()->create(['parent_id' => null]);
        $parent2 = Category::factory()->create(['parent_id' => null]);
        $child1 = Category::factory()->create(['parent_id' => $parent1->id, 'position' => 1]);
        $child2 = Category::factory()->create(['parent_id' => $parent2->id, 'position' => 1]);

        Livewire::test(ReOrderCategories::class)
            ->call('reorder', [
                (string) $child1->id,
                (string) $child2->id,
            ], (string) $parent2->id);

        expect($child1->refresh()->parent_id)->toBe($parent2->id)
            ->and($child1->refresh()->position)->toBe(1)
            ->and($child2->refresh()->position)->toBe(2);
    });

    it('can move a category to root level', function (): void {
        $parent = Category::factory()->create(['parent_id' => null, 'position' => 1]);
        $child = Category::factory()->create(['parent_id' => $parent->id, 'position' => 1]);

        Livewire::test(ReOrderCategories::class)
            ->call('reorder', [
                (string) $child->id,
                (string) $parent->id,
            ]);

        expect($child->refresh()->parent_id)->toBeNull()
            ->and($child->refresh()->position)->toBe(1)
            ->and($parent->refresh()->position)->toBe(2);
    });
})->group('livewire', 'slideovers', 'products');
