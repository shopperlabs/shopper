<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class UpdateVariantStock extends ModalComponent
{
    public $variant;

    public function mount(int $id)
    {
        $this->variant = (new ProductRepository())->getById($id);
    }

    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function render()
    {
        return view('shopper::livewire.modals.update-variant-stock');
    }
}
