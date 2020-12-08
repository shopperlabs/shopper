<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\Component;

class Attributes extends Component
{
    /**
     * Product Model.
     *
     * @var Model
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
     * @return View
     */
    public function render()
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }
}
