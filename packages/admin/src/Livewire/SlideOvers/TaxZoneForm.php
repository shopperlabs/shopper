<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Shopper\Components\Separator;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\TaxProvider;
use Shopper\Core\Models\TaxZone;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Schema $form
 */
class TaxZoneForm extends SlideOverComponent implements HasActions, HasForms, SlideOverForm
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithSlideOverForm;

    public ?TaxZone $taxZone = null;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(?int $taxZoneId = null): void
    {
        $this->authorize('access_setting');

        $this->taxZone = $taxZoneId
            ? TaxZone::with('country')->find($taxZoneId)
            : new TaxZone;

        $this->title = $taxZoneId
            ? $this->taxZone->display_name
            : __('shopper::pages/settings/taxes.add_action');

        $this->form->fill($this->taxZone->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('country_id')
                    ->label(__('shopper::forms.label.country'))
                    ->options(Country::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->native(false),
                TextInput::make('province_code')
                    ->label(__('shopper::pages/settings/taxes.province_code'))
                    ->placeholder('US-CA, FR-IDF, GB-ENG...')
                    ->helperText(__('shopper::pages/settings/taxes.province_code_help')),
                TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->placeholder('California, Île-de-France...')
                    ->helperText(__('shopper::pages/settings/taxes.name_help')),
                Toggle::make('is_tax_inclusive')
                    ->label(__('shopper::pages/settings/taxes.inclusive'))
                    ->helperText(__('shopper::pages/settings/taxes.inclusive_help')),
                Select::make('provider_id')
                    ->label(__('shopper::pages/settings/taxes.provider'))
                    ->options(TaxProvider::query()->where('is_enabled', true)->pluck('identifier', 'id'))
                    ->placeholder(__('shopper::pages/settings/taxes.system_default'))
                    ->native(false),
                Separator::make(),
                KeyValue::make('metadata')
                    ->label('Metadata')
                    ->reorderable(),
            ])
            ->statePath('data')
            ->model($this->taxZone);
    }

    public function store(): void
    {
        $this->authorize('access_setting');

        $data = $this->form->getState();

        if ($this->taxZone->id) {
            $this->taxZone->update($data);
        } else {
            $this->taxZone = TaxZone::query()->create($data);
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => $this->taxZone->display_name]))
            ->success()
            ->send();

        $this->redirectRoute('shopper.settings.taxes', ['zone' => $this->taxZone->id]);
    }
}
