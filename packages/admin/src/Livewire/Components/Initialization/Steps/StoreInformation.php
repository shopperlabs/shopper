<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization\Steps;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Traits\SaveSettings;
use Spatie\LivewireWizard\Components\StepComponent;

/**
 * @property-read Schema $form
 */
final class StoreInformation extends StepComponent implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use SaveSettings;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        /** @var Collection<int, Setting> $settings */
        $settings = Setting::query()->whereIn('key', [
            'name',
            'email',
            'country_id',
            'default_currency_id',
            'currencies',
        ])
            ->select('value', 'key')
            ->get();

        $this->form->fill(
            $settings->mapWithKeys(
                fn (Setting $item): array => [$item['key'] => $item['value']]
            )->toArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.store_name'))
                            ->prefixIcon(Untitledui::Shop)
                            ->inlinePrefix()
                            ->maxLength(100)
                            ->required(),
                        TextInput::make('email')
                            ->label(__('shopper::forms.label.email'))
                            ->prefixIcon(Untitledui::Mail)
                            ->inlinePrefix()
                            ->autocomplete('email-address')
                            ->placeholder('your@shopper.store')
                            ->email()
                            ->required(),
                    ]),
                Select::make('country_id')
                    ->label(__('shopper::forms.label.country'))
                    ->options(
                        Country::query()
                            ->select('name', 'id', 'region', 'cca2')
                            ->orderBy('name')
                            ->get()
                            ->sortBy('region')
                            ->groupBy('region')
                            ->map(fn ($region): array => $region->pluck('name', 'id')->toArray())
                    )
                    ->searchable()
                    ->native(false),
                Select::make('currencies')
                    ->label(__('shopper::forms.label.currencies'))
                    ->helperText(__('shopper::pages/onboarding.currencies_description'))
                    ->options(Currency::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->multiple()
                    ->minItems(1)
                    ->required()
                    ->live()
                    ->native(false),
                Select::make('default_currency_id')
                    ->label(__('shopper::forms.label.default_currency'))
                    ->helperText(__('shopper::pages/onboarding.currency_description'))
                    ->placeholder(__('shopper::forms.placeholder.select_currencies'))
                    ->options(
                        fn (Get $get) => Currency::query()
                            ->select('name', 'id')
                            ->whereIn('id', $get('currencies'))
                            ->pluck('name', 'id')
                    )
                    ->native(false)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->saveSettings($this->form->getState());

        $this->nextStep();

        Notification::make()
            ->title(__('shopper::notifications.store_info'))
            ->success()
            ->send();
    }

    /**
     * @return array<string, mixed>
     */
    public function stepInfo(): array
    {
        return [
            'label' => __('shopper::pages/onboarding.step_one_title'),
            'complete' => shopper_setting('name')
                && shopper_setting('email')
                && shopper_setting('country_id'),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.initialization.store-information');
    }
}
