<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class ReOrderCategories extends ModalComponent
{
    public function updateGroupOrder(array $items)
    {
        foreach ($items as $item) {
            (new CategoryRepository())
                ->getById($item['value'])
                ->update(['position' => $item['order']]);
        }

        $this->emitSelf('notify-saved');
        $this->emit('onCategoriesReordered');
    }

    public function updateCategoryOrder(array $groups)
    {
        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                (new CategoryRepository())
                    ->getById($item['value'])
                    ->update([
                        'parent_id' => $group['value'],
                        'position' => $item['order'],
                    ]);
            }
        }

        $this->emitSelf('notify-saved');

        $this->emit('onCategoriesReordered');
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.re-order-categories', [
            'categories' => (new CategoryRepository())
                ->with('childs')
                ->where('parent_id', null)
                ->orderBy('position')
                ->get(),
        ]);
    }
}
