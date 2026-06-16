<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Campaign;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Campaign;
use Shopper\Livewire\Concerns\InteractsWithCampaignForm;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class Edit extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithCampaignForm;
    use InteractsWithSchemas;
    use WithBreadcrumbs;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: $this->campaign->name),
        ];
    }

    public function mount(int $record): void
    {
        $this->authorize('campaigns.edit');

        $this->campaign = Campaign::query()
            ->with('discounts:id,campaign_id,code,type,value')
            ->findOrFail($record);

        $this->form->fill([
            'name' => $this->campaign->name,
            'currency_code' => $this->campaign->currency_code,
            'budget_type' => $this->campaign->budget_type->value,
            'budget_amount' => $this->campaign->budget_amount,
            'budget_count' => $this->campaign->budget_count,
            'starts_at' => $this->campaign->starts_at,
            'ends_at' => $this->campaign->ends_at,
            'metadata' => $this->campaign->metadata,
            'is_active' => $this->campaign->is_active,
        ]);
    }

    #[Computed]
    public function summary(): array
    {
        return $this->buildSummary();
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('shopper::forms.actions.delete'))
            ->icon(Untitledui::Trash03)
            ->color('danger')
            ->requiresConfirmation()
            ->authorize('campaigns.delete')
            ->action(function (): void {
                $this->authorize('campaigns.delete');

                $name = $this->campaign->name;
                $this->campaign->delete();

                Notification::make()
                    ->title(__('shopper::notifications.delete', [
                        'item' => __('shopper::pages/campaigns.single').' '.$name,
                    ]))
                    ->success()
                    ->send();

                $this->redirectRoute(name: 'shopper.campaigns.index', navigate: true);
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.campaigns.edit')
            ->title($this->campaign->name);
    }
}
