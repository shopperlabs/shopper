<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Shopper\Core\Events\Products\Deleted as ProductDeleted;
use Shopper\Core\Repositories\Store\ProductRepository;
use Shopper\Core\Traits\Attributes\WithUploadProcess;
use Shopper\Livewire\Components\Products\WithAttributes;

class Variants extends Component
{
    use WithAttributes;
    use WithFileUploads;
    use WithPagination;
    use WithUploadProcess;

    public string $search = '';

    public $product;

    public $quantity;

    public string $currency;

    protected $listeners = ['onVariantAdded' => 'render'];

    public function mount($product, string $currency): void
    {
        $this->product = $product;
        $this->currency = $currency;
    }

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function remove(int $id): void
    {
        $product = (new ProductRepository())->getById($id);

        event(new ProductDeleted($product));

        $product->forceDelete();

        $this->dispatchBrowserEvent('item-removed');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.variation_delete'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-variants', [
            'variants' => (new ProductRepository())
                ->makeModel()
                ->where(function (Builder $query): void {
                    $query->where('name', 'like', '%' . $this->search . '%');
                    $query->where('parent_id', $this->product->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }
}
