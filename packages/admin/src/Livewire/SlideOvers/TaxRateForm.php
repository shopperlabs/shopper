<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Shopper\Components\Separator;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Schema $form
 */
class TaxRateForm extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithSlideOverForm;

    public ?TaxRate $taxRate = null;

    public ?int $taxZoneId = null;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(int $taxZoneId, ?int $taxRateId = null): void
    {
        $this->authorize('access_setting');

        $this->taxZoneId = $taxZoneId;

        $this->taxRate = $taxRateId
            ? TaxRate::query()->find($taxRateId)
            : new TaxRate;

        $zone = TaxZone::with('country')->find($taxZoneId);

        $this->title = $taxRateId
            ? __('shopper::pages/settings/taxes.rates.update', ['name' => $this->taxRate->name])
            : __('shopper::pages/settings/taxes.rates.add_heading', ['name' => $zone->display_name]);

        $this->form->fill($this->taxRate->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->placeholder('VAT 20%, Sales Tax...')
                    ->required(),
                TextInput::make('code')
                    ->label(__('shopper::forms.label.code'))
                    ->placeholder('VAT20, GST...'),
                TextInput::make('rate')
                    ->label(__('shopper::pages/settings/taxes.rates.rate'))
                    ->numeric()
                    ->suffix('%')
                    ->required()
                    ->rules(['regex:/^\d{1,3}(\.\d{0,4})?$/']),
                Toggle::make('is_default')
                    ->label(__('shopper::words.default'))
                    ->helperText(__('shopper::pages/settings/taxes.rates.default_help')),
                Toggle::make('is_combinable')
                    ->label(__('shopper::pages/settings/taxes.rates.combinable'))
                    ->helperText(__('shopper::pages/settings/taxes.rates.combinable_help')),
                Separator::make(),
                KeyValue::make('metadata')
                    ->label('Metadata')
                    ->reorderable(),
            ])
            ->statePath('data')
            ->model($this->taxRate);
    }

    public function store(): void
    {
        $this->authorize('access_setting');

        $data = $this->form->getState();
        $data['tax_zone_id'] = $this->taxZoneId;

        if ($this->taxRate->id) {
            $this->taxRate->update($data);
        } else {
            $this->taxRate = TaxRate::query()->create($data);
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => $this->taxRate->name]))
            ->success()
            ->send();

        $this->redirectRoute('shopper.settings.taxes', ['zone' => $this->taxZoneId]);
    }
}
