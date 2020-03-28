<?php

namespace Shopper\Framework\Http\Components\Blade;

use Illuminate\View\Component;

class TimePicker extends Component
{
    public function render()
    {
        return view('shopper::components.blades.time-picker');
    }
}
