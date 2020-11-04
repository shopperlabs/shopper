<?php

namespace Shopper\Framework\Http\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class Payments extends Component
{
    /**
     * Stripe API key.
     *
     * @var string
     */
    public $stripe_key;

    /**
     * Stripe API secret.
     *
     * @var string
     */
    public $stripe_secret;

    /**
     * Mounted component.
     *
     * @return void
     */
    public function mount()
    {
        $this->stripe_key = env('STRIPE_KEY');
        $this->stripe_secret = env('STRIPE_SECRET');
    }

    /**
     * Updated Stripe payment.
     *
     * @return void
     */
    public function store()
    {
        setEnvironmentValue([
            'stripe_key'  => $this->stripe_key,
            'stripe_secret'      => $this->stripe_secret,
        ]);

        Artisan::call('config:clear');

        $this->dispatchBrowserEvent('notify', [
            'title' => __('Updated'),
            'message' => __("Your payments configuration settings have been correctly updated!")
        ]);
    }

    /**
     * Renter component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.payments');
    }
}
