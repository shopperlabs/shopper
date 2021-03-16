<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Shopper\Framework\Rules\RealEmailValidator;

class Configuration extends Component
{
    /**
     *  Mail mailer method.
     *
     * @var string
     */
    public $mail_mailer;

    /**
     * Mail host.
     *
     * @var string
     */
    public $mail_host;

    /**
     * Mail port.
     *
     * @var string
     */
    public $mail_port;

    /**
     * Mail username.
     *
     * @var string
     */
    public $mail_username;

    /**
     * Mail password config.
     *
     * @var string
     */
    public $mail_password;

    /**
     * Mail encryption.
     *
     * @var string
     */
    public $mail_encryption;

    /**
     * Mail sender address.
     *
     * @var string
     */
    public $mail_from_address;

    /**
     * Mail sender name.
     *
     * @var string
     */
    public $mail_from_name;

    /**
     * Component Mount instance.
     *
     * @return void
     */
    public function mount()
    {
        $this->mail_mailer = env('MAIL_MAILER');
        $this->mail_host = env('MAIL_HOST');
        $this->mail_port = env('MAIL_PORT');
        $this->mail_username = env('MAIL_USERNAME');
        $this->mail_password = env('MAIL_PASSWORD');
        $this->mail_encryption = env('MAIL_ENCRYPTION');
        $this->mail_from_address = env('MAIL_FROM_ADDRESS');
        $this->mail_from_name = env('MAIL_FROM_NAME');
    }

    /**
     * Sav/Update a entry on the .env file.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'mail_from_address' => ['required', 'email', new RealEmailValidator()],
            'mail_from_name' => 'required'
        ]);

        setEnvironmentValue([
            'mail_mailer'       => $this->mail_mailer,
            'mail_host'         => $this->mail_host,
            'mail_port'         => $this->mail_port,
            'mail_username'     => $this->mail_username,
            'mail_password'     => $this->mail_password,
            'mail_encryption'   => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name'    => $this->mail_from_name,
        ]);

        Artisan::call('config:clear');

        if (app()->environment('production')) {
            Artisan::call('config:cache');
        }

        $this->notify([
            'title' => __('Updated'),
            'message' => __("Your mail configurations have been correctly updated.")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.mails.configuration');
    }
}
