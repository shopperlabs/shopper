<?php

namespace Shopper\Framework\Http\Livewire\Settings\Payments;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Shopper\Framework\Models\Shop\PaymentMethod;
use Shopper\Framework\Models\System\Currency;
use Shopper\Framework\Models\System\Setting;

class Stripe extends Component
{
    /**
     * Stripe API public key.
     *
     * @var string
     */
    public $stripe_key;

    /**
     * Stripe API secret key.
     *
     * @var string
     */
    public $stripe_secret;

    /**
     * Stripe API Webhook.
     *
     * @var string
     */
    public $stripe_webhook_secret;

    /**
     * Stripe Mode.
     *
     * @var string
     */
    public $stripe_mode;


    /**
     * Cashier Currency.
     *
     * @var string
     */
    public $currency;

    /**
     * Indicates if Stripe Payment is being enabled.
     *
     * @var bool
     */
    public $enabled = false;

    /**
     * Mounted component.
     *
     * @return void
     */
    public function mount()
    {
        $this->enabled = ($stripe = PaymentMethod::query()->where('slug', 'stripe')->first()) ? $stripe->is_enabled : false;
        $this->stripe_mode = env('STRIPE_MODE', 'sandbox');
        $this->stripe_key = env('STRIPE_KEY', '');
        $this->stripe_secret = env('STRIPE_SECRET', '');
        $this->stripe_webhook_secret = env('STRIPE_WEBHOOK_SECRET', '');
        $this->currency = ($currency = Setting::query()
            ->where('key', 'shop_currency_id')
            ->first()) ? $currency->value: 'USD';
    }

    /**
     * Enabled Stripe payment.
     *
     * @return void
     */
    public function enabledStripe()
    {
        PaymentMethod::query()->create([
            'title' => 'Stripe',
            'link_url' => 'https://laravel.com/docs/billing',
            'is_enabled' => true,
            'description' => "Laravel Cashier provides an expressive, fluent interface to Stripe's subscription billing services. It handles almost all of the boilerplate subscription billing code you are dreading writing. In addition to basic subscription management, Cashier can handle coupons, swapping subscription, subscription 'quantities', cancellation grace periods, and even generate invoice PDFs."
        ]);

        $this->enabled = true;

        $this->dispatchBrowserEvent('notify', [
            'title' => __("Success"),
            'message' => __("You have successfully enabled Stripe payment for your store!"),
        ]);
    }

    /**
     * Updated Stripe payment.
     *
     * @return void
     */
    public function store()
    {
        setEnvironmentValue([
            'stripe_mode'       => $this->stripe_mode,
            'stripe_key'        => $this->stripe_key,
            'stripe_secret'     => $this->stripe_secret,
            'stripe_webhook_secret' => $this->stripe_webhook_secret,
            'cahier_currency'   => $this->currency,
        ]);

        Artisan::call('config:clear');

        $this->dispatchBrowserEvent('notify', [
            'title' => __('Updated'),
            'message' => __("Your Stripe payments configuration have been correctly updated!")
        ]);
    }

    /**
     * Renter component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.payments.stripe', [
            'currencies' => Currency::all(),
        ]);
    }
}
