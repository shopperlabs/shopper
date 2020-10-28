<?php

namespace Shopper\Framework\Components\Blade\Input;

use Illuminate\View\Component;

class FileUpload extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('shopper::components.input.file-upload');
    }
}