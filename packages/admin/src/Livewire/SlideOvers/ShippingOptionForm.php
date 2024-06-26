<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Shopper\Components\Separator;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property Zone $zone
 */
class ShippingOptionForm extends SlideOverComponent implements HasForms, SlideOverForm
{
    use InteractsWithForms;
    use InteractsWithSlideOverForm;

    public int $zoneId;

    public ?CarrierOption $option = null;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    public ?array $data = [];

    public function mount(?int $optionId = null): void
    {
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->placeholder('Standard option...')
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->label(__('shopper::forms.label.price'))
                            ->numeric()
                            ->required()
                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                            ->suffix($this->zone->currency->code)
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),

                        Forms\Components\Select::make('carrier_id')
                            ->label(__('shopper::pages/settings/carriers.title'))
                            ->options($this->zone->carriers->pluck('name', 'id'))
                            ->required()
                            ->native(false)
                            ->columnSpan('full'),

                        Forms\Components\Textarea::make('description')
                            ->label(__('shopper::forms.label.description'))
                            ->hint(__('shopper::words.characters', ['number' => 200]))
                            ->rows(3)
                            ->maxLength(200)
                            ->columnSpan('full'),
                    ])
                    ->columns(),

                Forms\Components\Toggle::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->helperText(__('shopper::pages/settings/zones.shipping_options.option_visibility')),

                Separator::make(),

                Forms\Components\KeyValue::make('metadata')
                    ->label('Metadata')
                    ->reorderable(),
            ])
            ->statePath('data')
            ->model($this->option);
    }

    public function store(): void
    {
        $data = array_merge($this->form->getState(), ['zone_id' => $this->zoneId]);

        if ($this->option) {
            $this->option->update($data);
        } else {
            $this->option = CarrierOption::query()->create($data);
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => $this->option->name]))
            ->success()
            ->send();

        $this->dispatch('refresh-zone', currentZoneId: $this->zoneId);

        $this->closePanel();
    }
}
