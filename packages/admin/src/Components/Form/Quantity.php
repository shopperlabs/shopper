<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\Concerns\HasName;
use Filament\Forms\Components\TextInput;

class Quantity extends TextInput
{
    use HasName;

    public ?string $heading = null;

    public bool $stacked = false;

    protected string $view = 'shopper::components.filament.forms.quantity';

    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric();
    }

    public function heading(?string $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function stacked(bool $stacked = true): static
    {
        $this->stacked = $stacked;

        return $this;
    }

    public function isStacked(): bool
    {
        return $this->stacked;
    }
}
