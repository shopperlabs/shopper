<?php

namespace Shopper\Framework\Http\Components\Blade;

use Illuminate\View\Component;

class DeleteAction extends Component
{
    /**
     * The component action.
     *
     * @var string
     */
    public string $action;

    /**
     * The component title.
     *
     * @var string
     */
    public string $title;

    /**
     * The component message.
     *
     * @var string
     */
    public string $message;

    /**
     * The component url.
     *
     * @var string
     */
    public string $url;

    /**
     * Create the component instance.
     *
     * @param  string  $action
     * @param  string  $title
     * @param  string  $message
     * @param  string  $url
     * @return void
     */
    public function __construct(string $action, string $title, string $message, string $url)
    {
        $this->action = $action;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('shopper::components.blades.delete-action');
    }
}
