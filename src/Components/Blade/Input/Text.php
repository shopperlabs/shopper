<?php

namespace Shopper\Framework\Components\Blade\Input;

use Illuminate\View\Component;

class Text extends Component
{
    public function render()
    {
        return view('shopper::components.input.text');
    }
}
