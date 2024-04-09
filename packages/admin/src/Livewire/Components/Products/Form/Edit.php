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
use Shopper\Feature;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public $product;

    public ?array $data = [];

    public function mount($product): void
    {
        $this->product = $product->load([
            'brand',
            'categories',
            'collections',
        ]);

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
                                    ->label(__('shopper::layout.forms.label.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set): void {
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->label(__('shopper::layout.forms.label.slug'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(config('shopper.models.product'), 'slug', ignoreRecord: true),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('shopper::layout.forms.label.description'))
                                    ->columnSpan('full'),
                            ]),

                        Components\Separator::make()
                            ->columnSpan('full'),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('price_amount')
                                    ->label(__('shopper::layout.forms.label.price_amount'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix(shopper_currency())
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                                Forms\Components\TextInput::make('old_price_amount')
                                    ->label(__('shopper::layout.forms.label.compare_price'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix(shopper_currency())
                                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                                Forms\Components\TextInput::make('cost_amount')
                                    ->label(__('shopper::layout.forms.label.cost_per_item'))
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
                                    ->label(__('shopper::layout.forms.label.visible'))
                                    ->helperText(__('shopper::pages/products.visible_help_text'))
                                    ->onColor('success')
                                    ->default(true),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('shopper::layout.forms.label.availability'))
                                    ->native(false)
                                    ->default(now())
                                    ->helperText(__('shopper::pages/products.availability_description'))
                                    ->required(),
                            ]),

                        Forms\Components\Section::make(__('shopper::pages/products.product_associations'))
                            ->schema([
                                Forms\Components\Select::make('brand_id')
                                    ->label(__('shopper::layout.forms.label.brand'))
                                    ->relationship('brand', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                                    ->searchable()
                                    ->visible(Feature::enabled('brand')),

                                Forms\Components\Select::make('collections')
                                    ->label(__('shopper::layout.sidebar.collections'))
                                    ->relationship('collections', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                                    ->searchable()
                                    ->multiple()
                                    ->visible(Feature::enabled('collection')),

                                Components\Form\SelectTree::make('categories')
                                    ->label(__('shopper::layout.sidebar.categories'))
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

        if (collect($data['categories'])->isNotEmpty()) {
            $this->product->categories()->sync($data['categories']);
        }

        event(new Updated($this->product));

        $this->dispatch('productHasUpdated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.edit');
    }
}
