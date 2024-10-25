<?php

declare(strict_types=1);

use Shopper\Core\Models\Category;
use Shopper\Core\Repositories\CategoryRepository;
use Shopper\Livewire\Pages;
use Shopper\Livewire\SlideOvers\CategoryForm;
use Shopper\Tests\Admin\Features\TestCase;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

uses(TestCase::class);

describe('Category', function (): void {
    it('can render categories page', function (): void {
        get(route('shopper.categories.index'))
            ->assertFound();

        livewire(Pages\Category\Index::class)
            ->assertSee(__('shopper::pages/categories.menu'));
    })->group('category');

    it('can validate `required` fields on add category form', function (): void {
        livewire(CategoryForm::class)
            ->assertFormExists()
            ->fillForm([])
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    })->group('category');

    it('can create a category', function (): void {
        livewire(CategoryForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'My new Category',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertDispatched('category-save');

        expect((new CategoryRepository)->count())->toBe(1);
    })->group('category');

    it('will generate a slug when brand slug already exists', function (): void {
        Category::factory()->create(['name' => 'Old category', 'slug' => 'my-first-category']);

        livewire(CategoryForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'My first category',
            ])
            ->call('save')
            ->assertDispatched('category-save');

        expect((new CategoryRepository)->count())
            ->toBe(2)
            ->and((new CategoryRepository)->getById(2)?->slug)
            ->toBe('my-first-category-1');
    })->group('category');

    it('can create category with parent', function (): void {
        $parent = Category::factory()->create(['name' => 'Parent']);

        livewire(CategoryForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'My new Category',
                'parent_id' => $parent->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertDispatched('category-save');

        expect((new CategoryRepository)->count())->toBe(2);
    })->group('category');

    it('has parent_id field null when parent category is deleted', function (): void {
        $parent = Category::factory()->create(['name' => 'Parent']);
        $child = Category::factory()->create(['name' => 'Child', 'parent_id' => $parent->id]);

        expect($child->parent_id)->toBe($parent->id);

        $parent->delete();
        $child->refresh();

        expect($child->parent_id)->toBeNull();
    })->group('category');
});
