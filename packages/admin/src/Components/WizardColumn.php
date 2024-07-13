<?php

declare(strict_types=1);

namespace Shopper\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\IconPosition;

class WizardColumn extends Wizard
{
    protected string $view = 'shopper::filament.wizard-column';

    public function getNextAction(): Action
    {
        $action = Action::make($this->getNextActionName())
            ->label(__('filament-forms::components.wizard.actions.next_step.label'))
            ->iconPosition(IconPosition::After)
            ->livewireClickHandlerEnabled(false)
            ->livewireTarget('dispatchFormEvent')
            ->color('primary')
            ->button();

        if ($this->modifyNextActionUsing) {
            $action = $this->evaluate($this->modifyNextActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }
}
