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
use Shopper\Traits\HandlesAuthorizationExceptions;

class DiscountCustomersPicker extends SlideOverComponent implements HasActions, HasSchemas, HasTable
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
        $userModel = config('auth.providers.users.model');

        return $table
            ->query(
                $userModel::query()
                    ->scopes('customers')
                    ->when(
                        $this->exceptIds,
                        fn (Builder $query) => $query->whereNotIn('id', $this->exceptIds)
                    )
            )
            ->columns([
                ImageColumn::make('picture')
                    ->label('')
                    ->circular()
                    ->grow(false),
                TextColumn::make('full_name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->searchable(),
            ])
            ->searchable()
            ->selectable()
            ->toolbarActions([
                BulkAction::make('add')
                    ->label(__('shopper::pages/discounts.customers_picker.bulk_add'))
                    ->icon(Untitledui::Plus)
                    ->action(function (Collection $records): void {
                        $ids = $records->pluck('id')
                            ->map(fn ($id): int => (int) $id)
                            ->all();

                        $this->dispatch('discount.customers.added', ids: $ids);
                        $this->dispatch('closePanel');
                    }),
            ])
            ->emptyStateIcon(Untitledui::Users)
            ->emptyStateHeading(__('shopper::pages/discounts.customers_picker.empty'));
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.discount-customers-picker');
    }
}
