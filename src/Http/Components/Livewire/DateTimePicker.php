<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Illuminate\View\Component;

class DateTimePicker extends Component
{
    public function render()
    {
        return view('shopper::components.blades.datetime-picker');
    }
}
