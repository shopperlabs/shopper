<?php

namespace Shopper\Framework\Components\Blade;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Back route name value.
     *
     * @var string
     */
    public string $back;

    /**
     * Breadcrumb Component.
     *
     * @param string $back
     */
    public function __construct($back = 'shopper.dashboard')
    {
        $this->back = $back;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('shopper::components.breadcrumb');
    }
}
