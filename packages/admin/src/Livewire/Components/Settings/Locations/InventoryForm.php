<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Locations;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Components\Form\AddressField;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Models\Contracts\Inventory;

/**
 * @property-read Schema $form
 */
class InventoryForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Model&Inventory $inventory;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->inventory->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/settings/global.location.detail'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/settings/global.location.detail_summary'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->placeholder('White House')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                                if ($state) {
                                    $set('code', Str::slug($state));
                                }
                            }),
                        Hidden::make('code'),
                        TextInput::make('email')
                            ->label(__('shopper::forms.label.email'))
                            ->autocomplete('email-address')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Textarea::make('description')
                            ->label(__('shopper::forms.label.description'))
                            ->rows(3)
                            ->columnSpan('full'),
                        Toggle::make('is_default')
                            ->label(__('shopper::pages/settings/global.location.set_default'))
                            ->helperText(__('shopper::pages/settings/global.location.set_default_summary')),
                        TextInput::make('priority')
                            ->label(__('shopper::forms.label.priority'))
                            ->helperText(__('shopper::pages/settings/global.location.priority_summary'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(),
                Separator::make(),
                Section::make(__('shopper::pages/settings/global.location.address'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/settings/global.location.address_summary'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
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
            resolve(Inventory::class)::query()->create($this->form->getState());
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => __('shopper::pages/settings/global.location.single')]))
            ->success()
            ->send();

        $this->redirectRoute(name: 'shopper.settings.locations', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.locations._form');
    }
}
