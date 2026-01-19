<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Section;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\InventoryHistory;

/**
 * @property-read Schema $form
 */
class Inventory extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    /** @var Model&Product */
    public Product $product;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->product->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/products.inventory.title'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/products.inventory.description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('sku')
                                    ->label(__('shopper::forms.label.sku'))
                                    ->unique(config('shopper.models.product'), 'sku', ignoreRecord: true)
                                    ->maxLength(255),
                                TextInput::make('barcode')
                                    ->label(__('shopper::forms.label.barcode'))
                                    ->unique(config('shopper.models.product'), 'barcode', ignoreRecord: true)
                                    ->maxLength(255),
                                TextInput::make('security_stock')
                                    ->label(__('shopper::forms.label.safety_stock'))
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
                    ->latest()
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('shopper::words.date'))
                    ->since()
                    ->sortable(),
                TextColumn::make('event')
                    ->label(__('shopper::words.event')),
                TextColumn::make('inventory.name')
                    ->label(__('shopper::pages/settings/menu.location')),
                TextColumn::make('adjustment')
                    ->label(__('shopper::words.adjustment'))
                    ->color(function (InventoryHistory $record): string {
                        if ($record->old_quantity <= 0) {
                            return 'danger';
                        }

                        return 'success';
                    })
                    ->alignRight(),
                TextColumn::make('quantity')
                    ->label(__('shopper::pages/products.inventory.movement'))
                    ->color(fn (InventoryHistory $record): string => $record->quantity <= 0 ? 'danger' : 'gray')
                    ->alignRight()
                    ->summarize([
                        Sum::make()
                            ->label(__('shopper::words.total'))
                            ->numeric(),
                    ]),
            ])
            ->headerActions([
                Action::make('stock')
                    ->label(__('shopper::forms.label.add_stock'))
                    ->icon(Untitledui::Package)
                    ->color('gray')
                    ->modalWidth(Width::Large)
                    ->schema([
                        Select::make('inventory')
                            ->label(__('shopper::pages/products.inventory_name'))
                            ->relationship('inventory', 'name')
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

                        $this->dispatch('inventory.updated');
                    }),
            ])
            ->filters([
                SelectFilter::make('inventory')
                    ->relationship('inventory', 'name')
                    ->searchable(),
            ])
            ->groups([
                Group::make('inventory.name')
                    ->label(__('shopper::pages/settings/menu.location'))
                    ->collapsible(),
            ])
            ->emptyStateIcon(Untitledui::File05)
            ->emptyStateDescription(__('shopper::pages/products.inventory.empty'));
    }

    public function store(): void
    {
        $this->product->update($this->form->getState());

        $this->dispatch('product.updated');

        Notification::make()
            ->title(__('shopper::pages/products.notifications.stock_update'))
            ->success()
            ->send();
    }

    #[On('inventory.updated')]
    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.inventory');
    }
}
