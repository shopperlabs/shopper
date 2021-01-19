<?php

namespace Shopper\Framework\Http\Livewire\Settings\Legal;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Legal;
use Shopper\Framework\Traits\WithLegalActions;

class Terms extends Component
{
    use WithLegalActions;

    /**
     * Legal page title.
     *
     * @var string
     */
    public $title = 'Terms of use';

    /**
     * Component mount instance.
     *
     * @return void
     */
    public function mount()
    {
        $legal = Legal::query()->where('slug', str_slug($this->title))->first();

        $this->initializeValues($legal);
    }

    /**
     * Store/Update data from storage.
     *
     * @return void
     */
    public function store()
    {
        $this->storeValues($this->title, $this->content, $this->isEnabled);

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Your terms of use has been successfully updated!'),
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.legal.terms');
    }
}
