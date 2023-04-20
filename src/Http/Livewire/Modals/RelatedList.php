<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use WireUi\Traits\Actions;

class RelatedList extends ModalComponent
{
    use Actions;

    public $product;

    public string $search = '';

    public array $exceptProductIds;

    public array $selectedProducts = [];

    public function mount(int $id, array $exceptProductIds = []): void
    {
        $this->product = (new ProductRepository())->getById($id);
        $this->exceptProductIds = $exceptProductIds;
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function getProductsProperty()
    {
        return (new ProductRepository())
            ->where('name', '%' . $this->search . '%', 'like')
            ->whereNull('parent_id')
            ->get(['name', 'price_amount', 'id'])
            ->except($this->exceptProductIds);
    }

    public function addSelectedProducts(): void
    {
        $currentProducts = $this->product->relatedProducts->pluck('id')->toArray();
        $this->product->relatedProducts()->sync(array_merge($this->selectedProducts, $currentProducts));

        $this->emit('onProductsAddInRelated');

        Notification::make()
            ->title(__('shopper::layout.status.added'))
            ->body(__('shopper::pages/products.notifications.related_added'))
            ->success()
            ->send();

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.related-lists');
    }
}
