<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Channel\Facades\Channels as ChannelDrivers;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Contracts\Channel as ChannelContract;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Channels extends Component implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    use WithSettingsBreadcrumbs;

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/settings/channels.title')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('system.settings');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                resolve(ChannelContract::class)::query()
                    ->orderByDesc('is_default')
                    ->orderBy('name')
            )
            ->columns([
                ImageColumn::make('logo')
                    ->label(__('shopper::forms.label.logo'))
                    ->circular()
                    ->getStateUsing(fn (Channel $record): ?string => ChannelDrivers::logoFor($record->driver))
                    ->defaultImageUrl(shopper_fallback_url()),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->description(fn (Channel $record): ?string => $record->url)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('driver')
                    ->label(__('shopper::forms.label.driver'))
                    ->badge()
                    ->default('web')
                    ->formatStateUsing(fn (string $state): string => $this->driverLabel($state))
                    ->color(fn (string $state): string => match (true) {
                        $state === 'web' => 'gray',
                        ChannelDrivers::isConfigured($state) => 'success',
                        default => 'warning',
                    }),
                IconColumn::make('is_default')
                    ->label(__('shopper::words.default'))
                    ->boolean(),
                ToggleColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.status'))
                    ->disabled(fn (Channel $record): bool => $record->is_default)
                    ->beforeStateUpdated(fn (): mixed => $this->authorize('system.settings')),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date(),
            ])
            ->emptyStateIcon('untitledui-shop')
            ->emptyStateDescription(__('shopper::pages/settings/channels.no_channel'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.channels')
            ->title(__('shopper::pages/settings/channels.title'));
    }

    protected function driverLabel(string $driver): string
    {
        return in_array($driver, ChannelDrivers::availableDrivers(), true)
            ? ChannelDrivers::driver($driver)->name()
            : $driver;
    }
}
