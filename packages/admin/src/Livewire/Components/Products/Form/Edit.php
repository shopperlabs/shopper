<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Components;
use Shopper\Core\Events\Products\Updated;
use Shopper\Core\Models\Product;
use Shopper\Feature;

/**
 * @property Forms\Form $form
 */
class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public Product $product;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->product->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('shopper::forms.label.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set): void {
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->label(__('shopper::forms.label.slug'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(config('shopper.models.product'), 'slug', ignoreRecord: true),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('shopper::forms.label.description'))
                                    ->columnSpan('full'),
                            ]),

                        Components\Separator::make()
                            ->columnSpan('full'),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('price_amount')  // @phpstan-ignore-line
                                    ->label(__('shopper::forms.label.price_amount'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix(shopper_currency())
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                                Forms\Components\TextInput::make('old_price_amount')  // @phpstan-ignore-line
                                    ->label(__('shopper::forms.label.compare_price'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix(shopper_currency())
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                                Forms\Components\TextInput::make('cost_amount')  // @phpstan-ignore-line
                                    ->label(__('shopper::forms.label.cost_per_item'))
                                    ->helperText(__('shopper::pages/products.cost_per_items_help_text'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix(shopper_currency())
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_visible')
                                    ->label(__('shopper::forms.label.visible'))
                                    ->helperText(__('shopper::pages/products.visible_help_text'))
                                    ->onColor('success')
                                    ->default(true),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('shopper::forms.label.availability'))
                                    ->native(false)
                                    ->default(now())
                                    ->helperText(__('shopper::pages/products.availability_description'))
                                    ->required(),
                            ]),

                        Forms\Components\Section::make(__('shopper::pages/products.product_associations'))
                            ->schema([
                                Forms\Components\Select::make('brand_id')
                                    ->label(__('shopper::forms.label.brand'))
                                    ->relationship('brand', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                                    ->searchable()
                                    ->visible(Feature::enabled('brand')),

                                Forms\Components\Select::make('collections')
                                    ->label(__('shopper::pages/collections.menu'))
                                    ->relationship('collections', 'name')
                                    ->searchable()
                                    ->multiple()
                                    ->visible(Feature::enabled('collection')),

                                Components\Form\SelectTree::make('categories')
                                    ->label(__('shopper::pages/categories.menu'))
                                    ->relationship(
                                        relationship: 'categories',
                                        titleAttribute: 'name',
                                        parentAttribute: 'parent_id'
                                    )
                                    ->searchable()
                                    ->independent(false)
                                    ->enableBranchNode()
                                    ->visible(Feature::enabled('category')),
                            ])
                            ->visible(
                                Feature::enabled('brand')
                                || Feature::enabled('category')
                                || Feature::enabled('collection')
                            ),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->product);
    }

    public function store(): void
    {
        $data = $this->form->getState();

        $this->product->update(Arr::except($data, ['categories']));

        if (Feature::enabled('category') && array_key_exists('categories', $data) && collect($data['categories'])->isNotEmpty()) {
            $this->product->categories()->sync($data['categories']);
        }

        event(new Updated($this->product));

        $this->dispatch('productHasUpdated');

        Notification::make()
            ->body(__('shopper::notifications.update', ['item' => __('shopper::pages/products.single')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.edit');
    }
}
