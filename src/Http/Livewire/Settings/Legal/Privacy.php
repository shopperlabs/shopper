<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Legal;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Traits\WithLegalActions;

class Privacy extends Component
{
    use WithLegalActions;

    public string $title = 'Privacy policy';

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

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('Your privacy policy has been successfully updated'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.legal.privacy');
    }
}
