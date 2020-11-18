<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;

class Create extends Component
{
    use WithFileUploads,
        WithUploadProcess,
        WithAttributes,
        WithSeoAttributes;

    /**
     * Default product stock quantity.
     *
     * @var mixed
     */
    public $quantity;

    /**
     * Real-time component validation.
     *
     * @param  string  $field
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    public function store()
    {
        $this->validate($this->rules());
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    protected function rules()
    {
        return [
            'name'  => 'bail|required',
            'sku'  => 'nullable|unique:'.shopper_table('products'),
            'barcode'  => 'nullable|unique:'.shopper_table('products'),
            'file' => 'nullable|image|max:1024',
            // 'brand_id' => 'integer|nullable|exists:'.shopper_table('brands').',id',
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.create');
    }
}
