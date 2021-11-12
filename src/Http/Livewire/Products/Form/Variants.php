<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithUploadProcess;

class Variants extends Component
{
    use WithPagination, WithFileUploads, WithAttributes, WithUploadProcess;

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

        $this->notify([
            'title' => __('Deleted'),
            'message' => __('The variation has successfully removed!'),
        ]);
    }

    public function render()
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
