<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Settings\Legal;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Traits\Attributes\WithLegalActions;

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
        $this->storeValues(__($this->title), $this->content, $this->isEnabled);

        Notification::make()
            ->body(__('shopper::notifications.legal'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.legal.privacy');
    }
}
