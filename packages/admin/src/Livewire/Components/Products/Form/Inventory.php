<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Components;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Traits\Attributes\WithStock;

class Inventory extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    // use WithStock;

    public $product;

    public ?array $data = [];

    public function mount($product): void
    {
        $this->product = $product;

        $this->form->fill($this->product->toArray());
        /*$this->inventories = $inventories;
        $this->inventory = $defaultInventory;
        $this->stock = $product->stock;
        $this->realStock = $product->stock;*/
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make(__('shopper::pages/products.inventory.title'))
                    ->description(__('shopper::pages/products.inventory.description'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label(__('shopper::layout.forms.label.sku'))
                                    ->unique(config('shopper.models.product'), 'sku', ignoreRecord: true)
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('barcode')
                                    ->label(__('shopper::layout.forms.label.barcode'))
                                    ->unique(config('shopper.models.product'), 'barcode', ignoreRecord: true)
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('security_stock')
                                    ->label(__('shopper::layout.forms.label.safety_stock'))
                                    ->helperText(__('shopper::pages/products.safety_security_help_text'))
                                    ->numeric()
                                    ->default(0)
                                    ->rules(['integer', 'min:0']),
                            ]),
                    ]),
            ])
            ->statePath('data')
            ->model($this->product);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                InventoryHistory::with(['inventory', 'stockable'])
                    ->where('stockable_id', $this->product->id)
                    ->where('stockable_type', 'product')
                    ->orderByDesc('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('shopper::words.date'))
                    ->since()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event')
                    ->label(__('shopper::words.event')),

                Tables\Columns\TextColumn::make('inventory.name')
                    ->label(__('shopper::words.location')),

                Tables\Columns\TextColumn::make('adjustment')
                    ->label(__('shopper::words.adjustment'))
                    ->color(function (InventoryHistory $record) {
                        if ($record->old_quantity > 0) {
                            return 'success';
                        }

                        if ($record->old_quantity <= 0) {
                            return 'danger';
                        }

                        return 'gray';
                    })
                    ->alignRight(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('shopper::pages/products.inventory.movement'))
                    ->color(fn (InventoryHistory $record) => $record->quantity <= 0 ? 'danger' : 'gray')
                    ->alignRight()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('shopper::words.total'))
                            ->numeric(),
                    ]),
            ])
            ->emptyStateIcon('untitledui-file-05')
            ->emptyStateDescription(__('shopper::pages/products.inventory.empty'))
            ->headerActions([
                Tables\Actions\Action::make('stock')
                    ->label('Add stock')
                    ->icon('untitledui-package')
                    ->modal()
                    ->color('gray')
                    ->modalWidth(MaxWidth::ExtraLarge)
                    ->form([
                        Forms\Components\Select::make('inventory')
                            ->label(__('shopper::pages/products.inventory_name'))
                            ->relationship('inventory', 'name')
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
                            ->where('stockable_id', $this->product->id)
                            ->where('stockable_type', 'product')
                            ->get()
                            ->sum('quantity');

                        $realTimeStock = $currentStock + $quantity;

                        if ($realTimeStock >= $currentStock) {
                            $this->product->mutateStock(
                                $inventoryId,
                                $quantity,
                                [
                                    'event' => __('shopper::pages/products.inventory.add'),
                                    'old_quantity' => $quantity,
                                ]
                            );
                        } else {
                            $this->product->decreaseStock(
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

                        $this->dispatch('updateInventory');
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inventory')
                    ->relationship('inventory', 'name')
                    ->native(false),
            ])
            ->groups([
                Tables\Grouping\Group::make('inventory.name')
                    ->label(__('shopper::words.location'))
                    ->collapsible(),
            ]);
    }

    public function store(): void
    {
        $this->validate([
            'sku' => [
                'nullable',
                Rule::unique(shopper_table('products'), 'sku')->ignore($this->product->id),
            ],
            'barcode' => [
                'nullable',
                Rule::unique(shopper_table('products'), 'barcode')->ignore($this->product->id),
            ],
        ]);

        $this->product->update($this->form->getState());
        $this->product->update([
            'sku' => $this->sku ?? null,
            'barcode' => $this->barcode ?? null,
            'security_stock' => $this->securityStock ?? null,
        ]);

        $this->dispatch('productHasUpdated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.stock_update'))
            ->success()
            ->send();
    }

    #[On('updateInventory')]
    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.inventory');
    }
}
