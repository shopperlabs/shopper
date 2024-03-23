<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization\Steps;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Validate;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Traits\SaveSettings;
use Spatie\LivewireWizard\Components\StepComponent;

final class StoreInformation extends StepComponent
{
    use SaveSettings;

    #[Validate('required|max:100')]
    public ?string $name = null;

    #[Validate('required|email')]
    public ?string $email = null;

    #[Validate('required|int')]
    public ?int $country_id = null;

    public ?int $currency_id = null;

    public ?string $about = null;

    public function mount(): void
    {
        $this->name = shopper_setting('name', false);
        $this->email = shopper_setting('email', false);
        $this->about = shopper_setting('about', false);
        $this->country_id = (int) shopper_setting('country_id', false);
        $this->currency_id = (int) shopper_setting('currency_id', false);
    }

    public function save(): void
    {
        $this->validate();

        $this->saveSettings([
            'name',
            'email',
            'about',
            'country_id',
            'currency_id',
        ]);

        $this->nextStep();
    }

    public function messages(): array
    {
        return [
            'country_id.required' => __('shopper::pages/settings.validations.country'),
            'name.required' => __('shopper::pages/settings.validations.shop_name'),
            'email.required' => __('shopper::pages/settings.validations.shop_name'),
        ];
    }

    public function stepInfo(): array
    {
        return [
            'label' => __('shopper::pages/settings.initialization.step_one_title'),
            'complete' => shopper_setting('name')
                && shopper_setting('email')
                && shopper_setting('country_id'),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.initialization.store-information', [
            'countries' => Cache::get('countries-settings', fn () => Country::query()->orderBy('name')->get()),
            'currencies' => Cache::get('currencies-setting', fn () => Currency::query()->orderBy('name')->get()),
        ]);
    }
}
