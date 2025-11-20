<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Shopper\Core\Repositories\CategoryRepository;
use Shopper\Livewire\Components\SlideOverComponent;

class ReOrderCategories extends SlideOverComponent
{
    /**
     * @param  array<array-key, mixed>  $items
     */
    public function updateGroupOrder(array $items): void
    {
        foreach ($items as $item) {
            (new CategoryRepository)
                ->getById((int) $item['value'])
                ->update(['position' => $item['order']]);
        }

        $this->dispatch('category-save');
    }

    /**
     * @param  array<array-key, mixed>  $groups
     */
    public function updateCategoryOrder(array $groups): void
    {
        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                (new CategoryRepository)
                    ->getById((int) $item['value'])
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
            'categories' => (new CategoryRepository)
                ->with('children')
                ->query()
                ->where('parent_id', null)
                ->orderBy('position')
                ->get(),
        ]);
    }
}
