<?php

namespace Shopper\Framework\Components\Blade;

use Illuminate\View\Component;

class LearnMore extends Component
{
    /**
     * Name to know more about.
     *
     * @var string
     */
    public string $name;

    /**
     * Link to redirect to the documentation.
     *
     * @var string
     */
    public string $link;

    /**
     * Breadcrumb Component.
     *
     * @param  string  $name
     * @param  string  $link
     */
    public function __construct($name, $link)
    {
        $this->name = $name;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('shopper::components.blades.learn-more');
    }
}
