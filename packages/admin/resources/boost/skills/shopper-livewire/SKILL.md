---
name: shopper-livewire
description: Provides patterns for building Livewire components in Shopper with Filament Forms, Tables, and Actions. Use when creating Pages, SlideOvers, or reusable components.
---

# Shopper Livewire Components

## Component Types

| Type      | Base Class              | Location               |
|-----------|-------------------------|------------------------|
| Page      | `AbstractPageComponent` | `Livewire/Pages/`      |
| SlideOver | `SlideOverComponent`    | `Livewire/SlideOvers/` |
| Component | `Component`             | `Livewire/Components/` |

## Page with Table

```php
class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
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

## Blade View Structure

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

## Blade Components

| Component                        | Purpose                              |
|----------------------------------|--------------------------------------|
| `<x-shopper::container>`         | Main content wrapper                 |
| `<x-shopper::card>`              | Card wrapper                         |
| `<x-shopper::heading :title="">` | Page title with optional action slot |
| `<x-shopper::breadcrumb>`        | Navigation breadcrumb                |
| `<x-shopper::separator>`         | Section separator                    |
| `<x-shopper::empty-card>`        | Empty state                          |
| `<x-filament::button>`           | Primary button                       |

## SlideOver with Form

```php
/**
 * @property-read Schema $form
 */
class BrandForm extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

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

## SlideOver Operations

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

## SlideOver Configuration

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

## Computed Properties

```php
#[Computed]
public function categories(): Collection
{
    return resolve(CategoryContract::class)::query()->get();
}
```

## Authorization

```php
// In mount
$this->authorize('browse_brands');

// In table actions
->visible(Shopper::auth()->user()->can('edit_brands'))
```
