<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Shopper\Components\Separator;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property Schema $form
 */
class ZoneForm extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithSlideOverForm;

    public ?Zone $zone = null;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @var array<array-key, int> */
    public array $countriesInZone = [];

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(?int $zoneId = null): void
    {
        $this->authorize('access_setting');

        $this->zone = $zoneId
            ? Zone::with(['countries', 'paymentMethods', 'carriers'])->find($zoneId)
            : new Zone;

        $this->title = $zoneId
            ? $this->zone->name
            : __('shopper::pages/settings/zones.add_action');

        $currentZoneCountryIds = $this->zone->id
            ? $this->zone->countries()->pluck('id')->all()
            : [];

        $this->countriesInZone = Country::query()
            ->whereHas('zones')
            ->whereNotIn('id', $currentZoneCountryIds)
            ->pluck('id')
            ->toArray();

        $countries = $this->zone->countries()->pluck('id')->all();
        $payments = $this->zone->paymentMethods()->pluck('id')->all();
        $carriers = $this->zone->carriers()->pluck('id')->all();

        $this->form->fill(array_merge(
            $this->zone->toArray(),
            ['countries' => $countries],
            ['payments' => $payments],
            ['carriers' => $carriers],
        ));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->placeholder('Africa')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if ($state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Hidden::make('slug'),
                        TextInput::make('code')
                            ->label(__('shopper::forms.label.code'))
                            ->placeholder('AF'),
                    ]),
                Select::make('countries')
                    ->label(__('shopper::forms.label.countries'))
                    ->placeholder(__('shopper::forms.placeholder.select_countries'))
                    ->multiple()
                    ->required()
                    ->options(
                        Country::query()
                            ->select('name', 'id')
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->disableOptionWhen(
                        fn (int $value): bool => in_array($value, $this->countriesInZone)
                    )
                    ->native(false),
                Select::make('currency_id')
                    ->label(__('shopper::forms.label.currency'))
                    ->placeholder(__('shopper::forms.placeholder.choose_currency'))
                    ->helperText(__('shopper::pages/settings/zones.currency_help'))
                    ->options(
                        Currency::query()
                            ->whereIn('id', shopper_setting('currencies', false))
                            ->pluck('name', 'id')
                    )
                    ->native(false)
                    ->required(),
                Toggle::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->helperText(__('shopper::words.set_visibility', ['name' => mb_strtolower(__('shopper::pages/settings/menu.zone'))])),
                Group::make()
                    ->schema([
                        TextEntry::make('providers')
                            ->label(__('shopper::pages/settings/zones.providers'))
                            ->state(new HtmlString(Blade::render(<<<'Blade'
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/settings/zones.providers_description') }}
                                </p>
                            Blade))),
                        Grid::make()
                            ->schema([
                                Select::make('payments')
                                    ->label(__('shopper::pages/settings/payments.title'))
                                    ->options(PaymentMethod::query()->pluck('title', 'id'))
                                    ->searchable()
                                    ->multiple()
                                    ->required(),
                                Select::make('carriers')
                                    ->label(__('shopper::pages/settings/carriers.title'))
                                    ->options(Carrier::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->multiple()
                                    ->required(),
                            ]),
                    ]),
                Separator::make(),
                KeyValue::make('metadata')
                    ->label('Metadata')
                    ->reorderable(),
            ])
            ->statePath('data')
            ->model($this->zone);
    }

    public function store(): void
    {
        $this->authorize('access_setting');

        $data = $this->form->getState();
        $validInputs = Arr::except($data, ['countries', 'payments', 'carriers']);

        if ($this->zone->id) {
            $this->zone->update($validInputs);
        } else {
            $this->zone = Zone::query()->create($validInputs);
        }

        if (array_key_exists('countries', $data)) {
            $this->zone->countries()->sync($data['countries']);
        }

        $this->zone->carriers()->sync($data['carriers']);
        $this->zone->paymentMethods()->sync($data['payments']);

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => $this->zone->name]))
            ->success()
            ->send();

        $this->redirectRoute('shopper.settings.zones', ['zone' => $this->zone->id]);
    }
}
