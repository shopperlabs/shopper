<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Taxes;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\TaxZone;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read ?TaxZone $taxZone
 */
#[Isolate]
#[Lazy]
class Detail extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    #[Reactive]
    public ?int $currentTaxZoneId = null;

    #[Computed]
    public function taxZone(): ?TaxZone
    {
        return TaxZone::with(['country', 'provider', 'rates'])
            ->find($this->currentTaxZoneId);
    }

    public function deleteAction(): Action
    {
        return DeleteAction::make('delete')
            ->authorize('system.settings')
            ->record($this->taxZone)
            ->icon(Untitledui::Trash03)
            ->iconButton()
            ->successNotificationTitle(__('shopper::notifications.delete', ['item' => __('shopper::pages/settings/taxes.single')]))
            ->after(function (): void {
                unset($this->taxZone);

                $this->dispatch('tax-zone-deleted');
            });
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->iconButton()
            ->icon(Untitledui::Edit03)
            ->action(fn (array $arguments) => $this->dispatch(
                'openPanel',
                'shopper-slide-overs.tax-zone-form',
                ['taxZoneId' => $arguments['id']]
            ));
    }

    public function placeholder(): View
    {
        return view('shopper::placeholders.detail-tax-zone');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.taxes.detail');
    }
}
