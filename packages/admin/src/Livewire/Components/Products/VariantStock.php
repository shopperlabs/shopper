<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Inventory;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Collection<int, Inventory> $inventories
 */
class VariantStock extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    #[Locked]
    public $variant;

    public function stockAction(): Action
    {
        return Action::make('stock')
            ->authorize('products.variants.edit')
            ->label(__('shopper::forms.actions.update'))
            ->color('gray')
            ->icon(Untitledui::Package)
            ->modalHeading(__('shopper::pages/products.modals.variants.title'))
            ->modalWidth(Width::Large)
            ->schema([
                Select::make('inventory')
                    ->label(__('shopper::pages/products.inventory_name'))
                    ->options(Inventory::query()->pluck('name', 'id'))
                    ->native(false)
                    ->required(),
                TextInput::make('quantity')
                    ->label(__('shopper::forms.label.quantity'))
                    ->placeholder('-10 or -5 or 50, etc')
                    ->numeric()
                    ->required(),
            ])
            ->action(function (array $data): void {
                $inventoryId = (int) $data['inventory'];
                $quantity = (int) $data['quantity'];
                $currentStock = $this->variant->stockInventory($inventoryId);

                if ($quantity >= 0) {
                    $this->variant->mutateStock(
                        inventoryId: $inventoryId,
                        quantity: $quantity,
                        oldQuantity: $currentStock,
                        event: __('shopper::pages/products.inventory.add'),
                        userId: auth()->id(),
                    );
                } else {
                    $this->variant->decreaseStock(
                        inventoryId: $inventoryId,
                        quantity: $quantity,
                        oldQuantity: $currentStock,
                        event: __('shopper::pages/products.inventory.remove'),
                        userId: auth()->id(),
                    );
                }

                Notification::make()
                    ->title(__('shopper::notifications.update', ['item' => __('shopper::words.stock')]))
                    ->success()
                    ->send();

                $this->dispatch('$refresh');
            });
    }

    /**
     * @return Collection<int, Inventory>
     */
    #[Computed]
    public function inventories(): Collection
    {
        return Inventory::query()->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.variant-stock');
    }
}
