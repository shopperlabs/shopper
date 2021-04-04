<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Exception;
use function count;
use Livewire\Component;
use function array_keys;
use Illuminate\Support\Arr;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Traits\WithSorting;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Models\Shop\Product\ProductAttribute;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Models\Shop\Product\ProductAttributeValue;
use Shopper\Framework\Repositories\Ecommerce\AttributeRepository;

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
     */
    public $quantity;

    /**
     * Variant product modal creation.
     *
     * @var bool
     */
    public $creatingModale = false;

    /**
     * Bulk variant product modal creation by attributes.
     *
     * @var bool
     */
    public $bulkModal = false;

    public $bulkAttributes = [];

    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Shopper default currency.
     *
     * @var string
     */
    public $currency;

    /**
     * All locations available on the store.
     */
    public $inventories;

    /**
     * All enabled attributes.
     */
    public $attributes = [];

    /**
     * Component mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $product
     * @param  string  $currency
     * @param  \Illuminate\Database\Eloquent\Collection  $inventories
     * @return void
     */
    public function mount($product, $currency, $inventories)
    {
        $this->product = $product;
        $this->currency = $currency;
        $this->inventories = $inventories;
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
     */
    public function openBulkVariantsModal()
    {
        $this->bulkModal = true;

        $this->attributes = (new AttributeRepository())
            ->where('is_enabled', true)
            ->get();
    }

    public function closeBulkVariantsModal()
    {
        $this->bulkModal = false;
        $this->bulkAttributes = [];
    }

    /**
     * Launch creation modale.
     */
    public function confirmVarianteCreating()
    {
        $this->creatingModale = true;
    }

    /**
     * Close variation creation modale.
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
            'name' => 'required|unique:' . shopper_table('products'),
            'sku' => 'nullable|unique:' . shopper_table('products'),
            'barcode' => 'nullable|unique:' . shopper_table('products'),
            'file' => 'nullable|image|max:1024',
        ];
    }

    /**
     * Store a newly entry to the storage.
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
            'message' => __('Product variation successfully added!'),
        ]);
    }

    /**
     * Remove a record to the database.
     *
     * @throws Exception
     */
    public function remove(int $id)
    {
        $product = (new ProductRepository())->getById($id);

        event(new ProductRemoved($product));

        $product->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->notify([
            'title' => __('Deleted'),
            'message' => __('The variation has successfully removed!'),
        ]);
    }

    public function generateVariants()
    {
        $keys = array_keys($this->bulkAttributes);
        $attributes = (new AttributeRepository())
            ->whereIn('id', $keys)
            ->with('values')
            ->get()
            ->keyBy('id');

        $attributes->each(fn ($a) => $a->_values = $a->values->keyBy('id'));

        $cartesian = Arr::crossJoin(...$this->bulkAttributes);

        foreach ($cartesian as $combination) {
            $sku = $this->product->sku;
            $name = $this->product->name;

            foreach ($combination as $idx => $attr) {
                $sku .= '-' . $attributes[$keys[$idx]]->_values[$attr]->key;
                $name .= ' ' . $attributes[$keys[$idx]]->_values[$attr]->key;
            }

            $product = $this->product->replicate(['slug']);
            $product->name = $name;
            $product->sku = $sku;
            $product->barcode = $sku;
            $product->parent_id = $this->product->id;
            $product->save();

            foreach ($combination as $idx => $attr) {
                $productAttribute = ProductAttribute::query()->create([
                    'product_id' => $product->id,
                    'attribute_id' => $attributes[$keys[$idx]]->id,
                ]);

                ProductAttributeValue::query()->create([
                    'attribute_value_id' => $attributes[$keys[$idx]]->_values[$attr]->id,
                    'product_attribute_id' => $productAttribute->id,
                ]);
            }
        }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        return  view('shopper::livewire.products.forms.form-variants', [
            'variants' => (new ProductRepository())
                ->makeModel()
                ->where(function (Builder $query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                    $query->where('parent_id', $this->product->id);
                })
                ->orderBy($this->sortBy ?? 'name', $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
