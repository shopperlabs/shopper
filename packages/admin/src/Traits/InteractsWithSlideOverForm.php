<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\View\View;

trait InteractsWithSlideOverForm
{
    use EvaluatesClosures;

    public function getTitle(): ?string
    {
        return $this->evaluate($this->title);
    }

    public function getDescription(): ?string
    {
        return $this->evaluate($this->description);
    }

    public function getAction(): ?string
    {
        return $this->evaluate($this->action);
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.form');
    }
}
