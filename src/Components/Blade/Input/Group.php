<?php

namespace Shopper\Framework\Components\Blade\Input;

use Illuminate\View\Component;

class Group extends Component
{
    /**
     * The input group label.
     *
     * @var string
     */
    public string $label;

    /**
     * The for label attribute.
     *
     * @var string
     */
    public string $for;

    /**
     * Define if input is required or not.
     *
     * @var bool
     */
    public bool $isRequired;

    /**
     * The input group error.
     *
     * @var null
     */
    public $error;

    /**
     * The Helper text.
     *
     * @var null
     */
    public $helpText;

    public function __construct(string $label, string $for, bool $isRequired = false, $error = null, $helpText = null)
    {
        $this->label = $label;
        $this->for = $for;
        $this->isRequired = $isRequired;
        $this->error = $error;
        $this->helpText = $helpText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('shopper::components.input.group');
    }
}