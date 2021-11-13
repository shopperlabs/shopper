<?php

namespace Shopper\Framework\Http\Livewire\Settings\Payments;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Shopper\Framework\Models\Shop\PaymentMethod;
use Shopper\Framework\Models\System\Currency;
use WireUi\Traits\Actions;

class Stripe extends Component
{
    use Actions;

    public string $stripe_key = '';
    public string $stripe_secret = '';
    public string $stripe_webhook_secret = '';
    public string $stripe_mode = 'sandbox';
    public string $currency;
    public bool $enabled = false;
    public string $message = '...';

    public function mount()
    {
        $this->enabled = ($stripe = PaymentMethod::query()->where('slug', 'stripe')->first()) ? $stripe->is_enabled : false;
        $this->stripe_mode = env('STRIPE_MODE', 'sandbox');
        $this->stripe_key = env('STRIPE_KEY', '');
        $this->stripe_secret = env('STRIPE_SECRET', '');
        $this->stripe_webhook_secret = env('STRIPE_WEBHOOK_SECRET', '');
        $this->currency = env('CASHIER_CURRENCY', shopper_currency());
    }

    public function enabledStripe()
    {
        PaymentMethod::query()->create([
            'title' => 'Stripe',
            'link_url' => 'https://laravel.com/docs/billing',
            'is_enabled' => true,
            'description' => "Laravel Cashier provides an expressive, fluent interface to Stripe's subscription billing services. It handles almost all of the boilerplate subscription billing code you are dreading writing. In addition to basic subscription management, Cashier can handle coupons, swapping subscription, subscription 'quantities', cancellation grace periods, and even generate invoice PDFs.",
        ]);

        $this->enabled = true;

        $this->notification()->success(__('Success'), __('You have successfully enabled Stripe payment for your store!'));
    }

    public function store()
    {
        Artisan::call('config:clear');

        setEnvironmentValue([
            'stripe_mode' => $this->stripe_mode,
            'stripe_key' => $this->stripe_key,
            'stripe_secret' => $this->stripe_secret,
            'stripe_webhook_secret' => $this->stripe_webhook_secret,
            'cashier_currency' => $this->currency,
        ]);

        $this->notification()->success(__('Updated'), __('Your Stripe payments configuration have been correctly updated!'));
    }

    public function render()
    {
        return view('shopper::livewire.settings.payments.stripe', [
            'currencies' => Cache::rememberForever('currencies', fn () => Currency::all()),
        ]);
    }
}
