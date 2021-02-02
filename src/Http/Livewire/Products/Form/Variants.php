<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Models\Shop\Inventory\Inventory;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithSorting;
use Shopper\Framework\Traits\WithUploadProcess;

class Variants extends Component
{
    use WithPagination,
        WithFileUploads,
        WithSorting,
        WithAttributes,
        WithUploadProcess;

    /**
     * Search.
     *
     * @var string
     */
    public $search;

    /**
     * Default product stock quantity.
     *
     * @var mixed
     */
    public $quantity;

    /**
     * Variant product modal creation.
     *
     * @var bool
     */
    public $creatingModale = false;

    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Component mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $product
     */
    public function mount($product)
    {
        $this->product = $product;
    }

    /**
     * Custom Livewire pagination view.
     *
     * @return string
     */
    public function paginationView()
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    /**
     * Launch creation modale.
     *
     * @return void
     */
    public function confirmVarianteCreating()
    {
        $this->creatingModale = true;
    }

    /**
     * Close variation creation modale.
     *
     * @return void
     */
    public function closeVarianteCreating()
    {
        $this->creatingModale = false;

        $this->name = null;
        $this->sku = null;
        $this->price_amount = null;
        $this->old_price_amount = null;
        $this->securityStock = 0;
        $this->barcode = null;
        $this->quantity = null;
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'name'  => 'required|unique:'.shopper_table('products'),
            'sku'  => 'nullable|unique:'.shopper_table('products'),
            'barcode'  => 'nullable|unique:'.shopper_table('products'),
            'file' => 'nullable|image|max:1024',
        ];
    }

    /**
     * Store a newly entry to the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        $product = (new ProductRepository())->create([
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'is_visible' => true,
            'security_stock' => $this->securityStock,
            'old_price_amount' => $this->old_price_amount,
            'price_amount' => $this->price_amount,
            'cost_amount' => $this->cost_amount,
            'type' => $this->product->type,
            'parent_id' => $this->product->id,
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.product'), $product->id);
        }

        if ($this->quantity && count($this->quantity) > 0) {
            foreach ($this->quantity as $inventory => $value) {
                $product->mutateStock(
                    $inventory,
                    $value,
                    [
                        'event' => __('Initial inventory'),
                        'old_quantity' => $value,
                    ]
                );
            }
        }

        $this->closeVarianteCreating();

        $this->notify([
            'title' => __('Added'),
            'message' => __('Product variation successfully added!')
        ]);
    }

    /**
     * Remove a record to the database.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function remove(int $id)
    {
        $product = (new ProductRepository())->getById($id);

        event(new ProductRemoved($product));

        $product->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->notify([
            'title' => __("Deleted"),
            'message' => __("The variation has successfully removed!")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        return  view('shopper::livewire.products.forms.form-variants', [
            'currency' => shopper_currency(),
            'inventories' => Inventory::query()->get(['name', 'id']),
            'variants' => (new ProductRepository())
                ->makeModel()
                ->where(function (Builder $query) {
                    $query->where('name', 'like', '%'. $this->search .'%');
                    $query->where('parent_id', $this->product->id);
                })
                ->orderBy($this->sortBy ?? 'name', $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
