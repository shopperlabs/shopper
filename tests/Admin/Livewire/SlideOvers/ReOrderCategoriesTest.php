<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Category;
use Shopper\Livewire\SlideOvers\ReOrderCategories;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
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

    it('can update group order', function (): void {
        $category1 = Category::factory()->create(['parent_id' => null, 'position' => 1]);
        $category2 = Category::factory()->create(['parent_id' => null, 'position' => 2]);
        $category3 = Category::factory()->create(['parent_id' => null, 'position' => 3]);

        Livewire::test(ReOrderCategories::class)
            ->call('updateGroupOrder', [
                ['value' => $category1->id, 'order' => 3],
                ['value' => $category2->id, 'order' => 1],
                ['value' => $category3->id, 'order' => 2],
            ])
            ->assertDispatched('category-save');

        $category1->refresh();
        $category2->refresh();
        $category3->refresh();

        expect($category1->position)->toBe(3)
            ->and($category2->position)->toBe(1)
            ->and($category3->position)->toBe(2);
    });

    it('can update category order with parent change', function (): void {
        $parent1 = Category::factory()->create(['parent_id' => null]);
        $parent2 = Category::factory()->create(['parent_id' => null]);
        $child = Category::factory()->create(['parent_id' => $parent1->id, 'position' => 1]);

        Livewire::test(ReOrderCategories::class)
            ->call('updateCategoryOrder', [
                [
                    'value' => $parent2->id,
                    'items' => [
                        ['value' => $child->id, 'order' => 2],
                    ],
                ],
            ])
            ->assertDispatched('category-save');

        $child->refresh();

        expect($child->parent_id)->toBe($parent2->id)
            ->and($child->position)->toBe(2);
    });

    it('can update multiple categories in different groups', function (): void {
        $parent1 = Category::factory()->create(['parent_id' => null]);
        $parent2 = Category::factory()->create(['parent_id' => null]);
        $child1 = Category::factory()->create(['parent_id' => $parent1->id]);
        $child2 = Category::factory()->create(['parent_id' => $parent1->id]);

        Livewire::test(ReOrderCategories::class)
            ->call('updateCategoryOrder', [
                [
                    'value' => $parent1->id,
                    'items' => [
                        ['value' => $child1->id, 'order' => 1],
                    ],
                ],
                [
                    'value' => $parent2->id,
                    'items' => [
                        ['value' => $child2->id, 'order' => 1],
                    ],
                ],
            ]);

        $child1->refresh();
        $child2->refresh();

        expect($child1->parent_id)->toBe($parent1->id)
            ->and($child1->position)->toBe(1)
            ->and($child2->parent_id)->toBe($parent2->id)
            ->and($child2->position)->toBe(1);
    });

    it('dispatches category-save event after updating group order', function (): void {
        $category = Category::factory()->create(['parent_id' => null, 'position' => 1]);

        Livewire::test(ReOrderCategories::class)
            ->call('updateGroupOrder', [
                ['value' => $category->id, 'order' => 2],
            ])
            ->assertDispatched('category-save');
    });

    it('dispatches category-save event after updating category order', function (): void {
        $parent = Category::factory()->create(['parent_id' => null]);
        $child = Category::factory()->create(['parent_id' => $parent->id]);

        Livewire::test(ReOrderCategories::class)
            ->call('updateCategoryOrder', [
                [
                    'value' => $parent->id,
                    'items' => [
                        ['value' => $child->id, 'order' => 1],
                    ],
                ],
            ])
            ->assertDispatched('category-save');
    });

    it('refreshes on category-save event', function (): void {
        $component = Livewire::test(ReOrderCategories::class);

        $component->dispatch('category-save');

        $component->assertOk();
    });
})->group('livewire', 'slideovers', 'products');
