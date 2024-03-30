<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Inventories;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Components\Form\AddressField;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Models\Inventory;

class InventoryForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Inventory $inventory;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->inventory->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('shopper::pages/settings.location.detail'))
                    ->description(__('shopper::pages/settings.location.detail_summary'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('shopper::layout.forms.label.name'))
                            ->placeholder('White House')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set): void {
                                $set('code', Str::slug($state));
                            }),
                        Forms\Components\Hidden::make('code'),
                        Forms\Components\TextInput::make('email')
                            ->label(__('shopper::layout.forms.label.email'))
                            ->autocomplete('email-address')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('shopper::layout.forms.label.description'))
                            ->rows(3)
                            ->columnSpan('full'),
                        Forms\Components\Toggle::make('is_default')
                            ->label(__('shopper::pages/settings.location.set_default'))
                            ->helperText(__('shopper::pages/settings.location.set_default_summary'))
                            ->columnSpan('full'),
                    ])
                    ->columns(),
                Separator::make(),
                Section::make(__('shopper::pages/settings.location.address'))
                    ->description(__('shopper::pages/settings.location.address_summary'))
                    ->aside()
                    ->compact()
                    ->schema(AddressField::make())
                    ->columns(),
            ])
            ->statePath('data')
            ->model($this->inventory);
    }

    public function store(): void
    {
        if ($this->inventory->id) {
            $this->inventory->update($this->form->getState());
        } else {
            Inventory::query()->create($this->form->getState());
        }

        Notification::make()
            ->body(__('shopper::pages/settings.notifications.inventory'))
            ->success()
            ->send();

        $this->redirectRoute(name: 'shopper.settings.inventories', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.inventories._form');
    }
}
