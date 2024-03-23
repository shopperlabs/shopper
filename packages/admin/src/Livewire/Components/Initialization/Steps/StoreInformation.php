<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Spatie\LivewireWizard\Components\StepComponent;

final class StoreInformation extends StepComponent
{
    #[Validate('required|max:100')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|int')]
    public ?int $country_id = null;

    public ?int $shop_currency_id = null;

    public string $about = '';

    public function save(): void
    {}

    public function render(): View
    {
        return view('');
    }
}
