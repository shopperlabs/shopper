<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class ReOrderCategories extends ModalComponent
{
    /**
     * Update category parent position.
     *
     * @param  array  $items
     *
     * @return void
     */
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

    /**
     * Update subcategory position.
     *
     * @param  array  $groups
     *
     * @return void
     */
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

    public function render()
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
