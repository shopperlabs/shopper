<?php

namespace Shopper\Framework\Components\Blade;

use Illuminate\View\Component;

class DateTimePicker extends Component
{
    /**
     * The component show state.
     *
     * @var bool
     */
    public $show;

    /**
     * The component published_at state.
     *
     */
    public $publishedAt;

    /**
     * Create the component instance.
     *
     * @param  bool  $show
     * @param  $publishedAt
     * @return void
     */
    public function __construct($publishedAt = null, bool $show = false)
    {
        $this->show = $show;
        $this->publishedAt = $publishedAt;
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
