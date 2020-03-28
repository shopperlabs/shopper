<?php

namespace Shopper\Framework\Http\Components\Blade;

use Illuminate\View\Component;

class DatePicker extends Component
{
    public function render()
    {
        return view('shopper::components.blades.date-picker');
    }
}
