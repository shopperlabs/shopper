<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;

class VariantStock extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $variant;

    public function mount($record): void
    {
        $this->variant = $record;
    }

    public function stockAction(): Action
    {
        return Action::make('stock')
            ->label(__('shopper::pages/products.variants.actions.update_stock'))
            ->color('gray')
            ->modal()
            ->icon('untitledui-package')
            ->modalHeading(__('shopper::pages/products.modals.variants.title'))
            ->modalWidth(MaxWidth::ExtraLarge)
            ->form([
                Forms\Components\Select::make('inventory')
                    ->label(__('shopper::pages/products.inventory_name'))
                    ->options(Inventory::query()->pluck('name', 'id'))
                    ->native(false)
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label(__('shopper::layout.forms.label.quantity'))
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
                    ->where('stockable_type', 'product')
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
                    ->title(__('Stock successfully Updated'))
                    ->success()
                    ->send();

                $this->dispatch('updateVariantInventory');
            });
    }

    #[On('updateVariantInventory')]
    public function render(): View
    {
        return view('shopper::livewire.components.products.variant-stock', [
            'inventories' => Inventory::query()->get(),
        ]);
    }
}
