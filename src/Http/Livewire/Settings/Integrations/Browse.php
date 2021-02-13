<?php

namespace Shopper\Framework\Http\Livewire\Settings\Integrations;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Channel;

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
     * Provider description for channel update.
     *
     * @var string
     */
    public $message = '';

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
     * @param  string|null  $message
     * @return void
     */
    public function confirmationEnable(string $provider, string $message = null)
    {
        $this->currentProvider = $provider;
        $this->confirmModalActivation = true;
        $this->message = $message
            ? __($message)
            : __('You are about to activate :provider for your store. This will allow you to have more options to improve your store.', ['provider' => $this->currentProvider]);
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

        if (in_array($this->currentProvider, $this->availableDatabaseChannels())) {
            $this->createChannel($this->currentProvider);
        }

        Artisan::call('config:clear');

        $this->closeIntegrationModal();

        $this->notify([
            'title' => __('Enabled'),
            'message' => __("You have been successfully enabled :provider", ['provider' => $provider])
        ]);
    }

    /**
     * Create a newly channel on the storage.
     *
     * @param  string  $channel
     * @return void
     */
    public function createChannel(string $channel)
    {
        Channel::query()->create([
            'name' => ucfirst($channel),
            'slug' => str_slug($channel),
        ]);
    }

    /**
     * Return the list of channel to add.
     *
     * @return string[]
     */
    protected function availableDatabaseChannels()
    {
        return [
            'twitter',
            'facebook',
            'instagram',
            'telegram',
        ];
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
