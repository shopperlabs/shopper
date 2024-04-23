<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization\Steps;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Shopper\Traits\SaveSettings;
use Spatie\LivewireWizard\Components\StepComponent;

final class StoreAddress extends StepComponent
{
    use SaveSettings;

    #[Validate('required|string')]
    public ?string $street_address = null;

    #[Validate('required')]
    public ?string $postcode = null;

    #[Validate('required')]
    public ?string $city = null;

    public ?string $phone_number = null;

    public function mount(): void
    {
        $this->street_address = shopper_setting('street_address', false);
        $this->city = shopper_setting('city', false);
        $this->postcode = shopper_setting('postcode', false);
        $this->phone_number = shopper_setting('phone_number', false);
    }

    public function save(): void
    {
        $this->validate();

        $this->saveSettings([
            'street_address',
            'city',
            'postcode',
            'phone_number',
        ]);

        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'label' => __('shopper::pages/settings.initialization.step_two_title'),
            'complete' => shopper_setting('street_address')
                && shopper_setting('city')
                && shopper_setting('postcode'),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.initialization.store-address');
    }
}
