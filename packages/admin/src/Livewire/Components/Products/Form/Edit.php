<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Actions\Store\Product\UpdateProductAction;
use Shopper\Components;
use Shopper\Core\Models\Product;
use Shopper\Feature;

/**
 * @property Forms\Form $form
 */
class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var array<array-key, mixed>|null
     */
    public ?array $data = [];

    public function mount($product): void
    {
        $this->product = $product;

        $this->form->fill($this->product->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make(__('shopper::pages/products.general'))
                            ->description($this->product->type?->getDescription())
                            ->icon($this->product->type?->getIcon())
                            ->iconSize(IconSize::Large)
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
                                Forms\Components\Textarea::make('summary')
                                    ->label(__('shopper::forms.label.summary'))
                                    ->columnSpan('full'),
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('shopper::forms.label.description'))
                                    ->columnSpan('full'),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Components\Separator::make()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('external_id')
                                            ->label(__('shopper::forms.label.external_id'))
                                            ->unique(config('shopper.models.product'), 'external_id', ignoreRecord: true)
                                            ->helperText(__('shopper::pages/products.external_id_description')),
                                    ])
                                    ->columnSpanFull()
                                    ->columns()
                                    ->visible($this->product->isExternal()),
                            ])
                            ->columns(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('shopper::pages/products.status'))
                            ->schema([
                                Forms\Components\Toggle::make('is_visible')
                                    ->label(__('shopper::forms.label.visible'))
                                    ->helperText(__('shopper::pages/products.visible_help_text'))
                                    ->onColor('success')
                                    ->default(true),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('shopper::forms.label.availability'))
                                    ->native(false)
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

                                SelectTree::make('categories')
                                    ->label(__('shopper::pages/categories.menu'))
                                    ->enableBranchNode()
                                    ->relationship(
                                        relationship: 'categories',
                                        titleAttribute: 'name',
                                        parentAttribute: 'parent_id',
                                        modifyQueryUsing: fn (Builder $query) => $query->where('is_enabled', true)
                                    )
                                    ->searchable()
                                    ->visible(Feature::enabled('category'))
                                    ->withCount(),

                                Forms\Components\Select::make('channels')
                                    ->label(__('shopper::pages/settings/menu.sales'))
                                    ->relationship(
                                        name: 'channels',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query) => $query->where('is_enabled', true)
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->multiple(),

                                Forms\Components\Select::make('collections')
                                    ->label(__('shopper::pages/collections.menu'))
                                    ->relationship('collections', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->visible(Feature::enabled('collection')),
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
        $this->validate();

        $this->product = app()->call(UpdateProductAction::class, [
            'form' => $this->form,
            'product' => $this->product,
        ]);

        $this->dispatch('product.updated');

        Notification::make()
            ->title(__('shopper::notifications.update', ['item' => __('shopper::pages/products.single')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.edit');
    }
}
