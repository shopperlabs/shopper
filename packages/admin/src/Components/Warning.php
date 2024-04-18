<?php

declare(strict_types=1);

namespace Shopper\Components;

use Filament\Forms\Components\Component;

class Warning extends Component
{
    protected string $view = 'shopper::components.filament.warning';

    protected ?string $feature = null;

    public static function make(): static
    {
        return app(static::class);
    }

    public function feature(string $feature): static
    {
        $this->feature = $feature;

        return $this;
    }

    public function getFeature(): ?string
    {
        return $this->evaluate($this->feature);
    }
}
