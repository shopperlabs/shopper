<?php

namespace Shopper\Framework\Http\Livewire\Settings\Legal;

use Livewire\Component;
use Shopper\Framework\Traits\WithLegalActions;
use WireUi\Traits\Actions;

class Shipping extends Component
{
    use Actions;
    use WithLegalActions;

    public string $title = 'Shipping policy';

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function onTrixValueUpdate($value)
    {
        $this->content = $value;
    }

    public function store()
    {
        $this->storeValues($this->title, $this->content, $this->isEnabled);

        $this->notification()->success(__('Updated'), __('Your shipping privacy has been successfully updated!'));
    }

    public function render()
    {
        return view('shopper::livewire.settings.legal.shipping');
    }
}
