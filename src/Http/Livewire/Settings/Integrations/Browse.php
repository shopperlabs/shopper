<?php

namespace Shopper\Framework\Http\Livewire\Settings\Integrations;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Browse extends Component
{
    /**
     * Github status integration.
     *
     * @var bool
     */
    public $github = false;

    /**
     * Twitter status integration.
     *
     * @var bool
     */
    public $twitter = false;

    /**
     * The current provider to setup.
     *
     * @var string
     */
    public $currentProvider = '';

    /**
     * Confirmation to launch modal to setup an integration.
     *
     * @var bool
     */
    public $confirmModalActivation = false;

    /**
     * Component mount instance.
     *
     * @return void
     */
    public function mount()
    {
        $this->github = env('SHOPPER_INTEGRATION_GITHUB', false);
        $this->twitter = env('SHOPPER_INTEGRATION_TWITTER', false);
    }

    /**
     * Confirmation modal.
     *
     * @param  string  $provider
     * @return void
     */
    public function confirmationEnable(string $provider)
    {
        $this->currentProvider = $provider;
        $this->confirmModalActivation = true;
    }

    /**
     * Enable provider and update environnement variables.
     *
     * @return void
     */
    public function enableProvider()
    {
        setEnvironmentValue(['shopper_integration_'. strtolower($this->currentProvider)  => true]);

        $this->{$this->currentProvider} = true;

        $provider = $this->currentProvider;

        Artisan::call('config:clear');

        $this->closeIntegrationModal();

        $this->notify([
            'title' => __('Enabled'),
            'message' => __("You have been successfully enabled :provider", ['provider' => $provider])
        ]);
    }

    /**
     * Close confirmation modal.
     *
     * @return void
     */
    public function closeIntegrationModal()
    {
        $this->confirmModalActivation = false;
        $this->currentProvider = '';
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.integrations.browse');
    }
}
