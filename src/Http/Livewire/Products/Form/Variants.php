<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithUploadProcess;
use WireUi\Traits\Actions;

class Variants extends Component
{
    use Actions;
    use WithPagination;
    use WithFileUploads;
    use WithAttributes;
    use WithUploadProcess;

    public string $search = '';

    public Model $product;

    public $quantity;

    public string $currency;

    protected $listeners = ['onVariantAdded' => 'render'];

    public function mount($product, string $currency)
    {
        $this->product = $product;
        $this->currency = $currency;
    }

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function remove(int $id)
    {
        $product = (new ProductRepository())->getById($id);

        event(new ProductRemoved($product));

        $product->forceDelete();

        $this->dispatchBrowserEvent('item-removed');

        $this->notification()->success(
            __('shopper::layout.status.delete'),
            __('shopper::pages/products.notifications.variation_delete')
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-variants', [
            'variants' => (new ProductRepository())
                ->makeModel()
                ->where(function (Builder $query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                    $query->where('parent_id', $this->product->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }
}
