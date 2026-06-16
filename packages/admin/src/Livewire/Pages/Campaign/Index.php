<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Campaign;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Enum\CampaignStatus;
use Shopper\Core\Models\Campaign;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\HasAuthenticated;

class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use HasAuthenticated;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('campaigns.browse');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Campaign::withCount('discounts')->latest())
            ->columns([
                TextColumn::make('name')
                    ->label(__('shopper::pages/campaigns.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->badge()
                    ->icon(fn (CampaignStatus $state): string => $state->getIcon())
                    ->color(fn (CampaignStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (CampaignStatus $state): string => $state->getLabel()),
                ViewColumn::make('budget')
                    ->label(__('shopper::pages/campaigns.budget'))
                    ->view('shopper::livewire.tables.cells.campaigns.budget'),
                TextColumn::make('discounts_count')
                    ->label(__('shopper::pages/campaigns.promotions'))
                    ->alignRight()
                    ->sortable(),
                ViewColumn::make('starts_at')
                    ->label(__('shopper::words.date'))
                    ->toggleable()
                    ->view('shopper::livewire.tables.cells.campaigns.date'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->url(fn (Campaign $record): string => route('shopper.campaigns.edit', $record))
                    ->authorize('campaigns.edit')
                    ->visible($this->getUser()->can('campaigns.edit')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Campaign $record): void {
                        $this->authorize('campaigns.delete');

                        $record->delete();
                    })
                    ->authorize('campaigns.delete')
                    ->visible($this->getUser()->can('campaigns.delete')),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $this->authorize('campaigns.delete');

                        $records->each->delete();

                        Notification::make()
                            ->title(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/campaigns.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->authorize('campaigns.delete')
                    ->visible($this->getUser()->can('campaigns.delete'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
                SelectFilter::make('budget_type')
                    ->label(__('shopper::pages/campaigns.budget_type'))
                    ->options(CampaignBudgetType::options()),
            ])
            ->filtersFormColumns(2)
            ->emptyState(view('shopper::livewire.tables.empty-states.campaigns'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.campaigns.index')
            ->title(__('shopper::pages/campaigns.menu'));
    }
}
