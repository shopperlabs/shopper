<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Str;

class SlugInput extends TextInput
{
    protected string $view = 'shopper::filament.form.slug-input';

    protected string|Closure|null $from = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('shopper::forms.label.slug'))
            ->dehydrateStateUsing(function (?string $state, Get $get): ?string {
                if (filled($state)) {
                    return Str::slug($state);
                }

                $from = $this->getFrom();

                return $from ? Str::slug((string) $get($from)) : null;
            });
    }

    public function from(string|Closure $field): static
    {
        $this->from = $field;

        return $this;
    }

    public function getFrom(): ?string
    {
        return $this->evaluate($this->from);
    }

    public function getFromStatePath(): ?string
    {
        $from = $this->getFrom();

        if (! $from) {
            return null;
        }

        $containerStatePath = $this->getContainer()->getStatePath();

        return $containerStatePath ? "{$containerStatePath}.{$from}" : $from;
    }
}
