<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class SidePanel extends Component
{
    #[Locked]
    public string $uniqueId;

    public bool $open = false;

    public string $title = 'Panel';

    public string $component = '';

    public function mount(): void
    {
        $this->uniqueId = uniqid(Str::random(8));
    }

    #[On('openPanel')]
    public function openPanel(string $title, string $component): void
    {
        $this->open = true;
        $this->title = $title;
        $this->component = $component;
    }

    #[On('closePanel')]
    public function closePanel(): void
    {
        $this->reset();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.side-panel');
    }
}
