<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization\Steps;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Core\Models\Inventory;
use Shopper\Traits\SaveSettings;
use Spatie\LivewireWizard\Components\StepComponent;

final class StoreSocialLink extends StepComponent implements HasForms
{
    use InteractsWithForms;
    use SaveSettings;

    public ?string $facebook_link = null;

    public ?string $instagram_link = null;

    public ?string $twitter_link = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('facebook_link')
                    ->prefix(
                        fn (): HtmlString => new HtmlString(Blade::render(<<<Blade
                            <x-shopper::icons.facebook
                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                aria-hidden="true"
                            />
                        Blade))
                    )
                    ->label(__('shopper::words.socials.facebook'))
                    ->placeholder('https://facebook.com/laravelshopper'),

                Forms\Components\TextInput::make('instagram_link')
                    ->prefix(
                        fn (): HtmlString => new HtmlString(Blade::render(<<<Blade
                            <x-shopper::icons.instagram
                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                aria-hidden="true"
                            />
                        Blade))
                    )
                    ->label(__('shopper::words.socials.instagram'))
                    ->placeholder('https://instagram.com/laravelshopper'),

                Forms\Components\TextInput::make('twitter_link')
                    ->prefix(
                        fn (): HtmlString => new HtmlString(Blade::render(<<<Blade
                            <x-shopper::icons.twitter
                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                aria-hidden="true"
                            />
                        Blade))
                    )
                    ->label(__('shopper::words.socials.twitter'))
                    ->placeholder('https://twitter.com/laravelshopper'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->saveSettings($this->form->getState());

        $this->storeHasSetup();

        Notification::make()
            ->title(__('shopper::notifications.store_info'))
            ->success()
            ->send();

        $this->redirectRoute('shopper.dashboard', navigate: true);
    }

    public function storeHasSetup(): void
    {
        Inventory::query()->create([
            'name' => $name = shopper_setting('name'),
            'code' => Str::slug($name),
            'email' => shopper_setting('email'),
            'street_address' => shopper_setting('street_address'),
            'postal_code' => shopper_setting('postal_code'),
            'city' => shopper_setting('city'),
            'phone_number' => shopper_setting('phone_number'),
            'country_id' => shopper_setting('country_id'),
            'is_default' => true,
        ]);
    }

    public function stepInfo(): array
    {
        return [
            'label' => __('shopper::pages/onboarding.step_tree_title'),
            'complete' => false,
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.initialization.store-social-link');
    }
}
