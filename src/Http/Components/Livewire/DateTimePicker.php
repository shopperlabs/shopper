<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Carbon\Carbon;
use Illuminate\View\Component;

class DateTimePicker extends Component
{
    /**
     * The component show state.
     *
     * @var bool
     */
    public bool $show;

    /**
     * The component published_at state.
     *
     * @var Carbon
     */
    public $published_at;

    /**
     * Create the component instance.
     *
     * @param  bool  $show
     * @param  Carbon  $published_at
     * @return void
     */
    public function __construct(bool $show = false, $published_at = null)
    {
        $this->show = $show;
        $this->published_at = $published_at;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('shopper::components.blades.datetime-picker');
    }
}
