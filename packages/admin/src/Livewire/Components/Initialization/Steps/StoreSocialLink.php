<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization\Steps;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Shopper\Core\Models\Inventory;
use Shopper\Traits\SaveSettings;
use Spatie\LivewireWizard\Components\StepComponent;

final class StoreSocialLink extends StepComponent
{
    use SaveSettings;

    public ?string $facebook_link = null;

    public ?string $instagram_link = null;

    public ?string $twitter_link = null;

    public function save(): void
    {
        $this->saveSettings([
            'facebook_link',
            'instagram_link',
            'twitter_link',
        ]);

        $this->storeHasSetup();

        $this->redirectRoute('shopper.dashboard');
    }

    public function storeHasSetup(): void
    {
        Inventory::query()->create([
            'name' => $name = shopper_setting('name'),
            'code' => Str::slug($name),
            'email' => shopper_setting('email'),
            'street_address' => shopper_setting('street_address'),
            'postcode' => shopper_setting('postcode'),
            'city' => shopper_setting('city'),
            'phone_number' => shopper_setting('phone_number'),
            'country_id' => shopper_setting('country_id'),
            'is_default' => true,
        ]);
    }

    public function stepInfo(): array
    {
        return [
            'label' => __('shopper::pages/settings.initialization.step_tree_title'),
            'complete' => false,
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.initialization.store-social-link');
    }
}
