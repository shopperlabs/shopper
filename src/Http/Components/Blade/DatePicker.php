<?php

namespace Shopper\Framework\Http\Components\Blade;

use Illuminate\View\Component;

class DatePicker extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('shopper::components.blades.date-picker');
    }
}
