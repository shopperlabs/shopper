<?php

namespace Shopper\Framework\Http\Components\Blade\Input;

use Illuminate\View\Component;

class RichText extends Component
{
    public function render()
    {
        return view('shopper::components.blades.input.rich-text');
    }
}