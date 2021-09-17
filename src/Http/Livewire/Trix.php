<?php

namespace Shopper\Framework\Http\Livewire;

use Livewire\Component;

class Trix extends Component
{
    public ?string $value = null;
    public string $trixId;

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
        return view('shopper::livewire.trix');
    }
}
