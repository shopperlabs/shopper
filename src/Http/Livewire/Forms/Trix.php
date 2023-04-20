<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Forms;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Trix extends Component
{
    public string $trixId;

    public ?string $value = null;

    public function mount(string $value = null): void
    {
        $this->value = $value;
        $this->trixId = 'trix-' . uniqid();
    }

    public function updatedValue(string $value): void
    {
        $this->emitUp('trix:valueUpdated', $value);
    }

    public function render(): View
    {
        return view('shopper::livewire.forms.trix');
    }
}
