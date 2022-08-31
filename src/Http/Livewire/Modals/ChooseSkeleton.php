<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class ChooseSkeleton extends ModalComponent
{
    public string $name;

    public string $type = 'html';

    public array $skeletons;

    public function mount(string $name, string $type, array $skeletons)
    {
        $this->name = $name;
        $this->type = $type;
        $this->skeletons = $skeletons;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.choose-skeleton');
    }
}
