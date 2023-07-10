<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products\Form;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RelatedProducts extends Component
{
    public $product;

    public $relatedProducts;

    protected $listeners = [
        'onProductsAddInRelated' => 'render',
    ];

    public function mount($product): void
    {
        $this->product = $product;
        $this->relatedProducts = $product->relatedProducts;
    }

    public function remove(int $id): void
    {
        $this->product->relatedProducts()->detach($id);
        $this->relatedProducts = $this->product->relatedProducts;

        $this->emitSelf('onProductsAddInRelated');

        Notification::make()
            ->title(__('shopper::layout.status.delete'))
            ->body(__('shopper::pages/products.notifications.remove_related'))
            ->success()
            ->send();
    }

    public function getProductsIdsProperty(): array
    {
        return array_merge($this->product->relatedProducts->modelKeys(), [$this->product->id]);
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-related');
    }
}
