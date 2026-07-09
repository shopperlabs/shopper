<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Locked;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;

class ProductsPicker extends SlideOverComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    /** @var array<int> */
    #[Locked]
    public array $exceptIds = [];

    #[Locked]
    public ?string $panelTitle = null;

    #[Locked]
    public ?string $panelDescription = null;

    #[Locked]
    public string $event = 'shopper.products.selected';

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public static function destroyOnClose(): bool
    {
        return true;
    }

    public static function dispatchCloseEvent(): bool
    {
        return true;
    }

    /**
     * @param  array<int>  $exceptIds
     */
    public function mount(array $exceptIds = [], ?string $ability = null, ?string $title = null, ?string $description = null, ?string $event = null): void
    {
        if ($ability !== null) {
            $this->authorize($ability);
        }

        $this->exceptIds = array_values(array_filter(array_map('intval', $exceptIds)));
        $this->panelTitle = $title;
        $this->panelDescription = $description;
        $this->event = $event ?? $this->event;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                resolve(Product::class)::query()
                    ->scopes('publish')
                    ->with('media')
                    ->when(
                        $this->exceptIds,
                        fn (Builder $query) => $query->whereNotIn('id', $this->exceptIds)
                    )
            )
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->getStateUsing(fn (Product $record): string => $record->getThumbnailUrl())
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->badge(),
                TextColumn::make('sku')
                    ->label(__('shopper::forms.label.sku')),
            ])
            ->searchable()
            ->selectable()
            ->toolbarActions([
                BulkAction::make('add')
                    ->label(__('shopper::pages/products.picker.bulk_add'))
                    ->icon(Untitledui::Plus)
                    ->action(function (Collection $records): void {
                        $ids = $records->pluck('id')
                            ->map(fn ($id): int => (int) $id)
                            ->all();

                        $this->dispatch($this->event, ids: $ids);
                        $this->dispatch('closePanel');
                    }),
            ])
            ->emptyStateIcon(Untitledui::Box)
            ->emptyStateHeading(__('shopper::pages/products.picker.empty'));
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.products-picker');
    }
}
