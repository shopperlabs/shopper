<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use function count;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class Edit extends AbstractBaseComponent
{
    use WithFileUploads;
    use WithUploadProcess;
    use WithAttributes;
    use WithSeoAttributes;

    public $product;

    public int $productId;

    public string $currency;

    public Collection $category_ids;

    public Collection $collection_ids;

    /**
     * Component Mount method.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     */
    public function mount($product, string $currency)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->brand_id = $product->brand_id;
        $this->description = $product->description;
        $this->isVisible = $product->is_visible;
        $this->price_amount = $product->price_amount;
        $this->old_price_amount = $product->old_price_amount;
        $this->cost_amount = $product->cost_amount;
        $this->type = $product->type;
        $this->publishedAt = $product->published_at->format('Y-m-d');
        $this->publishedAtFormatted = $product->published_at->toRfc7231String();
        $this->collection_ids = $product->collections->pluck('id');
        $this->category_ids = $product->categories->pluck('id');
        $this->currency = $currency;
    }

    public function store(): void
    {
        $this->validate($this->rules());

        $this->product->update([
            'name' => $this->name,
            'slug' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'is_visible' => $this->isVisible,
            'old_price_amount' => $this->old_price_amount,
            'price_amount' => $this->price_amount,
            'cost_amount' => $this->cost_amount,
            'published_at' => $this->publishedAt,
            'brand_id' => $this->brand_id,
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.product'), $this->productId);
        }

        if (count($this->category_ids) > 0) {
            $this->product->categories()->sync($this->category_ids);
        }

        if (count($this->collection_ids) > 0) {
            $this->product->collections()->sync($this->collection_ids);
        }

        event(new ProductUpdated($this->product));

        $this->emit('productHasUpdated', $this->productId);

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Product successfully updated!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-edit', [
            'brands' => (new BrandRepository())
                ->makeModel()
                ->scopes('enabled')
                ->select('name', 'id')
                ->get(),
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->scopes('enabled')
                ->select('name', 'id')
                ->get(),
            'collections' => (new CollectionRepository())->get(['name', 'id']),
        ]);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'file' => 'nullable|image|max:1024',
            'brand_id' => 'integer|nullable|exists:' . shopper_table('brands') . ',id',
        ];
    }
}
