---
name: shopper-development
description: Coding standards and patterns for Shopper monorepo development. Use when creating or modifying Models, Actions, Enums, Livewire components, migrations, tests, or building admin UI with Filament Schemas, Tables, and Actions.
license: MIT
metadata:
    author: shopperlabs
---

# Developing Shopper

## Monorepo Structure

| Package             | Namespace           | Purpose                                     |
|---------------------|---------------------|---------------------------------------------|
| `packages/admin`    | `Shopper\`          | Livewire components, views, routes, assets  |
| `packages/core`     | `Shopper\Core\`     | Models, Actions, Enums, Contracts           |
| `packages/cart`     | `Shopper\Cart\`     | Cart management, pipeline-based calculation |
| `packages/payment`  | `Shopper\Payment\`  | Payment processing, driver architecture     |
| `packages/shipping` | `Shopper\Shipping\` | Shipping providers, driver architecture     |
| `packages/sidebar`  | `Shopper\Sidebar\`  | Sidebar navigation builder                  |
| `packages/stripe`   | `Shopper\Stripe\`   | Stripe payment driver                       |
| `packages/types`    | —                   | TypeScript type definitions (NPM package)   |
| `packages/upgrade`  | —                   | Upgrade utilities                           |

## Required in Every PHP File

```php
<?php

declare(strict_types=1);
```

## Class Element Order

1. Traits → 2. Cases → 3. Constants → 4. Properties → 5. Constructor → 6. Magic methods → 7. Methods (public → protected → private)

## Models

See [MODELS.md](MODELS.md) for the swappable model pattern.

```php
class Product extends Model implements ProductContract
{
    use HasFactory;
    use HasModelContract;
    use HasSlug;

    protected $guarded = [];

    public static function configuredClass(): string
    {
        return config('shopper.models.product', static::class);
    }

    public function getTable(): string
    {
        return shopper_table('products');
    }

    protected function casts(): array
    {
        return ['featured' => 'boolean'];
    }
}
```

## Actions

```php
final class CreateOrderAction
{
    public function execute(array $data): Order
    {
        // Single responsibility
    }
}
```

## Enums

```php
enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Pending = 'pending';

    public function getLabel(): string
    {
        return __('shopper-core::enum/order.pending');
    }
}
```

## Livewire Components

```php
class ProductForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    #[Computed]
    public function categories(): Collection
    {
        return resolve(CategoryContract::class)::query()->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.product-form');
    }
}
```

## Migrations

```php
return new class extends \Shopper\Core\Helpers\Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('products'), function (Blueprint $table): void {
            $this->addCommonFields($table, hasSoftDelete: true);
            $this->addSeoFields($table);
            $this->addShippingFields($table);
            $this->addForeignKey($table, 'brand_id', $this->getTableName('brands'));
        });
    }
};
```

## Testing

```php
beforeEach(function (): void {
    $this->user = User::factory()->create();
});

it('creates a product', function (): void {
    $this->actingAs($this->user);

    Livewire::test(ProductForm::class)
        ->set('data.name', 'Test')
        ->call('save')
        ->assertHasNoErrors();
})->group('products');
```

## Commands

```bash
composer test:sqlite   # Run tests
composer test:types    # PHPStan
composer cs            # Rector + Pint + Prettier
```

## Livewire Patterns

### Component Types

| Type      | Base Class                                            | Location               |
|-----------|-------------------------------------------------------|------------------------|
| Page      | `AbstractPageComponent`                               | `Livewire/Pages/`      |
| SlideOver | `SlideOverComponent` (`Laravelcm\LivewireSlideOvers`) | `Livewire/SlideOvers/` |
| Component | `Component`                                           | `Livewire/Components/` |

### Page with Table

```php
class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_brands');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(resolve(BrandContract::class)::query()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->action(fn ($record) => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.brand-form',
                        arguments: ['brand' => $record]
                    )),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.brand.index')
            ->title(__('shopper::pages/brands.menu'));
    }
}
```

### Blade View Structure

```blade
<x-shopper::container>
    {{-- Breadcrumb --}}
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('Shipping')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.index')"
            :title="__('Settings')"
        />
    </x-shopper::breadcrumb>

    {{-- Page Heading --}}
    <x-shopper::heading class="my-6" :title="__('Shipping Methods')">
        <x-slot name="action">
            <x-filament::button wire:click="create">
                {{ __('Add Method') }}
            </x-filament::button>
        </x-slot>
    </x-shopper::heading>

    {{-- Content --}}
    <x-shopper::card class="mt-5">
        {{ $this->table }}
    </x-shopper::card>
</x-shopper::container>
```

### Blade Components

| Component                        | Purpose                              |
|----------------------------------|--------------------------------------|
| `<x-shopper::container>`         | Main content wrapper                 |
| `<x-shopper::card>`              | Card wrapper                         |
| `<x-shopper::heading :title="">` | Page title with optional action slot |
| `<x-shopper::breadcrumb>`        | Navigation breadcrumb                |
| `<x-shopper::separator>`         | Section separator                    |
| `<x-shopper::empty-card>`        | Empty state                          |
| `<x-filament::button>`           | Primary button                       |

### SlideOver with Form

```php
/**
 * @property-read Schema $form
 */
class BrandForm extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Brand $brand;
    public ?array $data = [];

    public function mount(?Brand $brand = null): void
    {
        $this->brand = $brand ?? resolve(Brand::class)::query()->newModelInstance();
        $this->form->fill($this->brand->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::words.general'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state))),
                        Hidden::make('slug'),
                    ]),
            ])
            ->statePath('data')
            ->model($this->brand);
    }

    public function save(): void
    {
        if ($this->brand->id) {
            $this->authorize('edit_brands', $this->brand);
            $this->brand->update($this->form->getState());
        } else {
            $this->authorize('add_brands');
            $brand = resolve(Brand::class)::query()->create($this->form->getState());
            $this->form->model($brand)->saveRelationships();
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => __('shopper::pages/brands.single')]))
            ->success()
            ->send();

        $this->redirectRoute('shopper.brands.index', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.brand-form');
    }
}
```

### SlideOver Operations

```php
// Open
$this->dispatch(
    'openPanel',
    component: 'shopper-slide-overs.brand-form',
    arguments: ['brand' => $record]
);

// Close
$this->closePanel();

// Close with events
$this->closePanelWithEvents(['refresh']);
```

### SlideOver Configuration

```php
public static function panelMaxWidth(): string
{
    return '2xl'; // sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
}

public static function closePanelOnClickAway(): bool
{
    return false;
}
```

### Computed Properties

```php
#[Computed]
public function categories(): Collection
{
    return resolve(CategoryContract::class)::query()->get();
}
```

### Authorization

```php
// In mount
$this->authorize('browse_brands');

// In table actions
->visible(Shopper::auth()->user()->can('edit_brands'))
```
