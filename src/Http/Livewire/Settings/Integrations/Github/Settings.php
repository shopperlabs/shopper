<?php

namespace Shopper\Framework\Http\Livewire\Settings\Integrations\Github;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Settings extends Component
{
    /**
     * Github API public key.
     *
     * @var string
     */
    public $github_key;

    /**
     * Github API secret key.
     *
     * @var string
     */
    public $github_secret;

    /**
     * Github API Webhook.
     *
     * @var string
     */
    public $github_webhook_url;

    /**
     * Component mount instance.
     *
     * @return void
     */
    public function mount()
    {
        $this->github_key = env('SHOPPER_INTEGRATION_GITHUB_KEY', '');
        $this->github_secret = env('SHOPPER_INTEGRATION_GITHUB_SECRET', '');
        $this->github_webhook_url = env('SHOPPER_INTEGRATION_GITHUB_WEBHOOK_URL', '');
    }

    /**
     * Updated Stripe payment.
     *
     * @return void
     */
    public function store()
    {
        setEnvironmentValue([
            'shopper_integration_github_key'         => $this->github_key,
            'shopper_integration_github_secret'      => $this->github_secret,
            'shopper_integration_github_webhook_url' => $this->github_webhook_url,
        ]);

        Artisan::call('config:clear');

        $this->notify([
            'title' => __('Updated'),
            'message' => __("Your Github API Keys configuration have been correctly updated!")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.integrations.github.settings');
    }
}
