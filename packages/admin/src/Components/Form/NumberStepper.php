<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\TextInput;

class NumberStepper extends TextInput
{
    protected string $view = 'shopper::filament.form.number-stepper';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->numeric()
            ->step(1);
    }
}
