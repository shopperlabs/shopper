<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Actions\Store\Product\UpdateProductAction;
use Shopper\Components\Separator;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\ProductTag;
use Shopper\Feature;

/**
 * @property-read Schema $form
 */
class Edit extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Model&Product $product;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->product->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make(__('shopper::pages/products.general'))
                            ->description($this->product->type?->getDescription())
                            ->icon($this->product->type?->getIcon())
                            ->iconSize(IconSize::Large)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('shopper::forms.label.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        if ($state) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->label(__('shopper::forms.label.slug'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(config('shopper.models.product'), 'slug', ignoreRecord: true),
                                Textarea::make('summary')
                                    ->label(__('shopper::forms.label.summary'))
                                    ->columnSpan('full'),
                                Toggle::make('featured')
                                    ->label(__('shopper::forms.label.featured'))
                                    ->helperText(__('shopper::pages/products.featured_help_text'))
                                    ->onColor('success')
                                    ->default(true),
                                RichEditor::make('description')
                                    ->label(__('shopper::forms.label.description'))
                                    ->columnSpan('full'),
                                Group::make()
                                    ->schema([
                                        Separator::make()->columnSpanFull(),
                                        TextInput::make('external_id')
                                            ->label(__('shopper::forms.label.external_id'))
                                            ->required()
                                            ->unique(config('shopper.models.product'), 'external_id', ignoreRecord: true)
                                            ->helperText(__('shopper::pages/products.external_id_description')),
                                        Select::make('supplier_id')
                                            ->label(__('shopper::forms.label.supplier'))
                                            ->required()
                                            ->relationship('supplier', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                                            ->searchable()
                                            ->preload()
                                            ->visible(Feature::enabled('supplier')),
                                    ])
                                    ->columnSpanFull()
                                    ->columns()
                                    ->visible($this->product->isExternal()),
                            ])
                            ->columns()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columnSpan(['lg' => 2]),
                Group::make()
                    ->schema([
                        Section::make(__('shopper::pages/products.status'))
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label(__('shopper::forms.label.visible'))
                                    ->helperText(__('shopper::pages/products.visible_help_text'))
                                    ->onColor('success')
                                    ->default(true),
                                DateTimePicker::make('published_at')
                                    ->label(__('shopper::forms.label.availability'))
                                    ->native(false)
                                    ->helperText(__('shopper::pages/products.availability_description'))
                                    ->required(),
                            ]),
                        Section::make(__('shopper::pages/products.product_associations'))
                            ->schema([
                                Select::make('brand_id')
                                    ->label(__('shopper::forms.label.brand'))
                                    ->relationship('brand', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                                    ->searchable()
                                    ->preload()
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
                                Select::make('channels')
                                    ->label(__('shopper::pages/settings/menu.sales'))
                                    ->relationship(
                                        name: 'channels',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query) => $query->where('is_enabled', true)
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->multiple(),
                                Select::make('collections')
                                    ->label(__('shopper::pages/collections.menu'))
                                    ->relationship('collections', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->visible(Feature::enabled('collection')),
                                Select::make('tags')
                                    ->label(__('shopper::pages/tags.menu'))
                                    ->relationship('tags', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label(__('shopper::forms.label.name'))
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (?string $state, Set $set): mixed => $set('slug', Str::slug($state ?? ''))),
                                        TextInput::make('slug')
                                            ->label(__('shopper::forms.label.slug'))
                                            ->disabled()
                                            ->dehydrated()
                                            ->required()
                                            ->unique(ProductTag::class, 'slug'),
                                    ])
                                    ->createOptionModalHeading(__('shopper::pages/tags.create'))
                                    ->createOptionAction(fn (Action $action): Action => $action->modalWidth('md'))
                                    ->visible(Feature::enabled('tag')),
                            ])
                            ->visible(
                                Feature::enabled('brand')
                                || Feature::enabled('category')
                                || Feature::enabled('collection')
                                || Feature::enabled('tag')
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
            'values' => $this->form->getState(),
            'product' => $this->product,
        ]);

        $this->form->model($this->product)->saveRelationships();

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
