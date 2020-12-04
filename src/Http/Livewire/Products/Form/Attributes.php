<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Livewire\Component;

class Attributes extends Component
{
    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Component Mount method.
     *
     * @return void
     */
    public function mount($product)
    {
        $this->product = $product;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }
}
