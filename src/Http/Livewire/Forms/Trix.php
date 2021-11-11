<?php

namespace Shopper\Framework\Http\Livewire\Forms;

use Livewire\Component;

class Trix extends Component
{
    public string $trixId;
    public ?string $value = null;

    public function mount(string $value = null)
    {
        $this->value = $value;
        $this->trixId = 'trix-' . uniqid();
    }

    public function updatedValue($value)
    {
        $this->emitUp('trix:valueUpdated', $value);
    }

    public function render()
    {
        return view('shopper::livewire.forms.trix');
    }
}
