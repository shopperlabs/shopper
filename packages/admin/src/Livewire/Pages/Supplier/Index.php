<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Supplier;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Supplier as SupplierContract;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_suppliers');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(resolve(SupplierContract::class)::query()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->placeholder('-'),
                TextColumn::make('contact_name')
                    ->label(__('shopper::pages/suppliers.contact'))
                    ->placeholder('-'),
                TextColumn::make('phone')
                    ->label(__('shopper::forms.label.phone_number'))
                    ->placeholder('-'),
                IconColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->action(
                        fn (SupplierContract $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.supplier-form',
                            arguments: ['supplier' => $record]
                        )
                    )
                    ->visible(Shopper::auth()->user()->can('edit_suppliers')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (SupplierContract $record) => $record->delete())
                    ->visible(Shopper::auth()->user()->can('delete_suppliers')),
            ])
            ->groupedBulkActions([
                BulkAction::make('enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->icon(Untitledui::CheckVerified)
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(); // @phpstan-ignore-line

                        Notification::make()
                            ->title(
                                __('shopper::notifications.enabled', [
                                    'item' => __('shopper::pages/suppliers.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('disabled')
                    ->label(__('shopper::forms.actions.disable'))
                    ->icon(Untitledui::SlashCircle01)
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(false); // @phpstan-ignore-line

                        Notification::make()
                            ->title(__('shopper::components.tables.status.updated'))
                            ->body(
                                __('shopper::notifications.disabled', [
                                    'item' => __('shopper::pages/suppliers.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/suppliers.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->visible(Shopper::auth()->user()->can('delete_suppliers'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                TernaryFilter::make('is_enabled'),
            ])
            ->persistFiltersInSession()
            ->emptyState(view('shopper::livewire.tables.empty-states.suppliers'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.supplier.index')
            ->title(__('shopper::pages/suppliers.menu'));
    }
}
