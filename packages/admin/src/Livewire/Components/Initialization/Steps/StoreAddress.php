<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization\Steps;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Setting;
use Shopper\Traits\SaveSettings;
use Spatie\LivewireWizard\Components\StepComponent;

final class StoreAddress extends StepComponent implements HasForms
{
    use InteractsWithForms;
    use SaveSettings;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::query()->whereIn('key', [
            'street_address',
            'city',
            'postal_code',
            'phone_number',
        ])
            ->select('value', 'key')
            ->get();

        $this->form->fill(
            $settings->mapWithKeys(
                fn (Setting $item) => [$item['key'] => $item['value']]
            )->toArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('street_address')
                    ->label(__('shopper::forms.label.street_address'))
                    ->placeholder('Akwa Avenue 34')
                    ->columnSpan(['lg' => 2])
                    ->required(),

                Forms\Components\TextInput::make('postal_code')
                    ->label(__('shopper::forms.label.postal_code'))
                    ->placeholder('00237')
                    ->required(),

                Forms\Components\TextInput::make('city')
                    ->label(__('shopper::forms.label.city'))
                    ->required(),

                Forms\Components\TextInput::make('phone_number')
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
