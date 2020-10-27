<?php

namespace Shopper\Framework\Components\Blade\Input;

use Illuminate\View\Component;

class RichText extends Component
{
    public $initialValue;

    /**
     * Create the component instance.
     *
     * @param  string  $initialValue
     * @return void
     */
    public function __construct(string $initialValue = null)
    {
        $this->initialValue = $initialValue;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('shopper::components.input.rich-text');
    }
}