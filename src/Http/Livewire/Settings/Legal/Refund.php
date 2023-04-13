<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Legal;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Traits\WithLegalActions;
use WireUi\Traits\Actions;

class Refund extends Component
{
    use Actions;
    use WithLegalActions;

    public string $title = 'Refund policy';

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function onTrixValueUpdate(string $value): void
    {
        $this->content = $value;
    }

    public function store(): void
    {
        $this->storeValues($this->title, $this->content, $this->isEnabled);

        $this->notification()->success(__('Updated'), __('Your refund policy has been successfully updated!'));
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.legal.refund');
    }
}
