<?php

namespace Shopper\Framework\Http\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Shopper\Framework\Models\System\Setting;

class Payments extends Component
{
    public $stripe_key;
    public $stripe_secret;

    public function mount() {
        $this->stripe_key = env('STRIPE_KEY');
        $this->stripe_secret = env('STRIPE_SECRET');
    }

    public function store()
    {
        setEnvironmentValue([
            'stripe_key'  => $this->stripe_key,
            'stripe_secret'      => $this->stripe_secret,
        ]);

        Artisan::call('config:clear');

        $this->dispatchBrowserEvent('notify', [
            'title' => __('Updated'),
            'message' => __("Your payments configuration settings have been correctly updated")
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.settings.payments');
    }
}
