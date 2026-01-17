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
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;

class VariantStock extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public $variant;

    public function stockAction(): Action
    {
        return Action::make('stock')
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

                $currentStock = InventoryHistory::query()
                    ->where('inventory_id', $inventoryId)
                    ->where('stockable_id', $this->variant->id)
                    ->where('stockable_type', config('shopper.models.variant'))
                    ->get()
                    ->sum('quantity');

                $realTimeStock = $currentStock + $quantity;

                if ($realTimeStock >= $currentStock) {
                    $this->variant->mutateStock(
                        $inventoryId,
                        $quantity,
                        [
                            'event' => __('shopper::pages/products.inventory.add'),
                            'old_quantity' => $quantity,
                        ]
                    );
                } else {
                    $this->variant->decreaseStock(
                        $inventoryId,
                        $quantity,
                        [
                            'event' => __('shopper::pages/products.inventory.remove'),
                            'old_quantity' => $quantity,
                        ]
                    );
                }

                Notification::make()
                    ->title(__('shopper::notifications.update', ['item' => __('shopper::words.stock')]))
                    ->success()
                    ->send();

                $this->dispatch('$refresh');
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.variant-stock', [
            'inventories' => Inventory::query()->get(),
        ]);
    }
}
