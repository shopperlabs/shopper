<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Shopper\Core\Models\Contracts\Category;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class ReOrderCategories extends SlideOverComponent
{
    use HandlesAuthorizationExceptions;

    /**
     * @param  array<string, mixed>  $items
     */
    public function updateGroupOrder(array $items): void
    {
        $this->authorize('edit_categories');

        foreach ($items as $item) {
            resolve(Category::class)::query()
                ->findOrFail((int) $item['value'])
                ->update(['position' => $item['order']]);
        }

        $this->dispatch('category-save');
    }

    /**
     * @param  array<string, mixed>  $groups
     */
    public function updateCategoryOrder(array $groups): void
    {
        $this->authorize('edit_categories');

        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                resolve(Category::class)::query()
                    ->findOrFail((int) $item['value'])
                    ->update([
                        'parent_id' => (int) $group['value'],
                        'position' => $item['order'],
                    ]);
            }
        }

        $this->dispatch('category-save');
    }

    #[On('category-save')]
    public function render(): View
    {
        return view('shopper::livewire.slide-overs.re-order-categories', [
            'categories' => resolve(Category::class)::query()
                ->with('children')
                ->whereNull('parent_id')
                ->orderBy('position')
                ->get(),
        ]);
    }
}
