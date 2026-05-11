<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
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

class DiscountProductsPicker extends SlideOverComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    /** @var array<int> */
    #[Locked]
    public array $exceptIds = [];

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    /**
     * @param  array<int>  $exceptIds
     */
    public function mount(array $exceptIds = []): void
    {
        $this->authorize('discounts.create');

        $this->exceptIds = array_values(array_filter(array_map('intval', $exceptIds)));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                resolve(Product::class)::query()
                    ->scopes('publish')
                    ->when(
                        $this->exceptIds,
                        fn (Builder $query) => $query->whereNotIn('id', $this->exceptIds)
                    )
            )
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(shopper_fallback_url()),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),
                TextColumn::make('sku')
                    ->label(__('shopper::forms.label.sku')),
            ])
            ->searchable()
            ->selectable()
            ->toolbarActions([
                BulkAction::make('add')
                    ->label(__('shopper::pages/discounts.products_picker.bulk_add'))
                    ->icon(Untitledui::Plus)
                    ->action(function (Collection $records): void {
                        $ids = $records->pluck('id')
                            ->map(fn ($id): int => (int) $id)
                            ->all();

                        $this->dispatch('discount.products.added', ids: $ids);
                        $this->dispatch('closePanel');
                    }),
            ])
            ->emptyStateIcon(Untitledui::Box)
            ->emptyStateHeading(__('shopper::pages/discounts.products_picker.empty'));
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.discount-products-picker');
    }
}
