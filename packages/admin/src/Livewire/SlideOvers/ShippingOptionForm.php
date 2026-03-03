<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Livewire\Attributes\Computed;
use Shopper\Components\Separator;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Zone $zone
 * @property-read Schema $form
 */
class ShippingOptionForm extends SlideOverComponent implements HasActions, HasForms, SlideOverForm
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithSlideOverForm;

    public int $zoneId;

    public ?CarrierOption $option = null;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(?int $optionId = null): void
    {
        $this->authorize('access_setting');

        $this->option = CarrierOption::query()
            ->where('zone_id', $this->zoneId)
            ->find($optionId);

        $this->title = $optionId
            ? __('shopper::pages/settings/zones.shipping_options.update', ['name' => $this->option->name])
            : __('shopper::pages/settings/zones.shipping_options.add_heading', ['name' => $this->zone->name]);

        $this->form->fill($this->option?->toArray());
    }

    #[Computed]
    public function zone(): Zone
    {
        return Zone::with(['currency', 'carriers'])->find($this->zoneId);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->placeholder('Standard option...')
                            ->required(),
                        TextInput::make('price') // @phpstan-ignore-line
                            ->label(__('shopper::forms.label.price'))
                            ->numeric()
                            ->required()
                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                            ->suffix($this->zone->currency->code)
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                        Select::make('carrier_id')
                            ->label(__('shopper::pages/settings/carriers.title'))
                            ->options($this->zone->carriers->pluck('name', 'id'))
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label(__('shopper::forms.label.description'))
                            ->hint(__('shopper::words.characters', ['number' => 200]))
                            ->rows(3)
                            ->maxLength(200)
                            ->columnSpanFull(),
                    ])
                    ->columns(),
                Toggle::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->helperText(__('shopper::pages/settings/zones.shipping_options.option_visibility')),
                Separator::make(),
                KeyValue::make('metadata')
                    ->label(__('Metadata'))
                    ->reorderable(),
            ])
            ->statePath('data')
            ->model($this->option);
    }

    public function store(): void
    {
        $this->authorize('access_setting');

        $data = array_merge($this->form->getState(), ['zone_id' => $this->zoneId]);

        if ($this->option) {
            $this->option->update($data);
        } else {
            $this->option = CarrierOption::query()->create($data);
        }

        $this->form->model($this->option)->saveRelationships();

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => $this->option->name]))
            ->success()
            ->send();

        $this->dispatch('zone.changed', currentZoneId: $this->zoneId);

        $this->closePanel();
    }
}
