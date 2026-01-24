<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization\Steps;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Shopper\Core\Models\Setting;
use Shopper\Traits\SaveSettings;
use Spatie\LivewireWizard\Components\StepComponent;

/**
 * @property-read Schema $form
 */
final class StoreAddress extends StepComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;
    use SaveSettings;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        /** @var Collection<int, Setting> $settings */
        $settings = Setting::query()->whereIn('key', [
            'street_address',
            'city',
            'state',
            'postal_code',
            'phone_number',
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
                TextInput::make('street_address')
                    ->label(__('shopper::forms.label.street_address'))
                    ->placeholder('Akwa Avenue 34')
                    ->columnSpan(['lg' => 2])
                    ->required(),
                TextInput::make('postal_code')
                    ->label(__('shopper::forms.label.postal_code'))
                    ->placeholder('00237')
                    ->required(),
                TextInput::make('state')
                    ->label(__('shopper::forms.label.state'))
                    ->placeholder('Littoral')
                    ->columnSpan(['lg' => 2]),
                TextInput::make('city')
                    ->label(__('shopper::forms.label.city'))
                    ->required(),
                TextInput::make('phone_number')
                    ->label(__('shopper::forms.label.phone_number'))
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function save(): void
    {
        $this->saveSettings($this->form->getState());

        Notification::make()
            ->title(__('shopper::notifications.store_info'))
            ->success()
            ->send();

        $this->nextStep();
    }

    /**
     * @return array<string, mixed>
     */
    public function stepInfo(): array
    {
        return [
            'label' => __('shopper::pages/onboarding.step_two_title'),
            'complete' => shopper_setting('street_address')
                && shopper_setting('city')
                && shopper_setting('postal_code'),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.initialization.store-address');
    }
}
