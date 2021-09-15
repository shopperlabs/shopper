<?php

namespace Shopper\Framework\Http\Livewire\Settings\Integrations;

use function in_array;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Shopper\Framework\Models\Shop\Channel;

class Browse extends Component
{
    /**
     * Github status integration.
     */
    public bool $github = false;

    /**
     * Twitter status integration.
     */
    public bool $twitter = false;

    /**
     * The current provider to setup.
     */
    public string $currentProvider = '';

    /**
     * Provider description for channel update.
     */
    public string $message = '';

    /**
     * Confirmation to launch modal to setup an integration.
     */
    public bool $confirmModalActivation = false;

    /**
     * Component mount instance.
     */
    public function mount()
    {
        $this->github = env('SHOPPER_INTEGRATION_GITHUB', false);
        $this->twitter = env('SHOPPER_INTEGRATION_TWITTER', false);
    }

    /**
     * Confirmation modal.
     */
    public function confirmationEnable(string $provider, ?string $message = null)
    {
        $this->currentProvider = $provider;
        $this->confirmModalActivation = true;
        $this->message = $message
            ? __($message)
            : __('You are about to activate :provider for your store. This will allow you to have more options to improve your store.', ['provider' => $this->currentProvider]);
    }

    /**
     * Enable provider and update environnement variables.
     */
    public function enableProvider()
    {
        setEnvironmentValue(['shopper_integration_' . mb_strtolower($this->currentProvider) => true]);

        $this->{$this->currentProvider} = true;

        $provider = $this->currentProvider;

        if (in_array($this->currentProvider, $this->availableDatabaseChannels())) {
            $this->createChannel($this->currentProvider);
        }

        Artisan::call('config:clear');

        $this->closeIntegrationModal();

        $this->notify([
            'title' => __('Enabled'),
            'message' => __('You have been successfully enabled :provider', ['provider' => $provider]),
        ]);
    }

    /**
     * Create a newly channel on the storage.
     */
    public function createChannel(string $channel)
    {
        Channel::query()->create([
            'name' => ucfirst($channel),
            'slug' => str_slug($channel),
        ]);
    }

    /**
     * Close confirmation modal.
     */
    public function closeIntegrationModal()
    {
        $this->confirmModalActivation = false;
        $this->currentProvider = '';
    }

    public function render()
    {
        return view('shopper::livewire.settings.integrations.browse');
    }

    /**
     * Return the list of channel to add.
     *
     * @return array<string>
     */
    protected function availableDatabaseChannels(): array
    {
        return [
            'twitter',
            'facebook',
            'instagram',
            'telegram',
        ];
    }
}
