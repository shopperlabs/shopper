<?php

declare(strict_types=1);

use Shopper\Core\Models\Brand;
use Shopper\Core\Repositories\BrandRepository;
use Shopper\Livewire\SlideOvers\BrandForm;
use Shopper\Tests\Admin\Features\TestCase;

use function Pest\Livewire\livewire;

uses(TestCase::class);

describe('Brand', function (): void {
    it('can render brand page', function (): void {
        $this->get($this->prefix . '/brands');

        livewire(\Shopper\Livewire\Pages\Brand\Index::class)
            ->assertSee(__('shopper::pages/brands.menu'));
    });

    it('can validate `required` fields on brand form', function (): void {
        livewire(BrandForm::class)
            ->assertFormExists()
            ->fillForm([])
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('can create brand', function (): void {
        livewire(BrandForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'Nike',
            ])
            ->call('save')
            ->assertDispatched('brand-save');
    });

    it('will generate a slug when brand slug already exists', function (): void {
        Brand::factory()->create(['name' => 'Nike Old', 'slug' => 'nike']);

        livewire(BrandForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'Nike',
            ])
            ->call('save')
            ->assertDispatched('brand-save');

        expect((new BrandRepository)->count())
            ->toBe(2)
            ->and((new BrandRepository)->getById(2)?->slug)
            ->toBe('nike-1');
    });
})->group('brand');
