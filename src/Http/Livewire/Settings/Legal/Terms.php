<?php

namespace Shopper\Framework\Http\Livewire\Settings\Legal;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Legal;
use Shopper\Framework\Traits\WithLegalActions;
use WireUi\Traits\Actions;

class Terms extends Component
{
    use Actions, WithLegalActions;

    public string $title = 'Terms of use';

    public function mount()
    {
        $legal = Legal::query()->where('slug', str_slug($this->title))->first();

        $this->initializeValues($legal);
    }

    public function store()
    {
        $this->storeValues($this->title, $this->content, $this->isEnabled);

        $this->notification()->success(__('Updated'), __('Your terms of use has been successfully updated!'));
    }

    public function render()
    {
        return view('shopper::livewire.settings.legal.terms');
    }
}
