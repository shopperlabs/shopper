<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Shopper\Framework\Rules\RealEmailValidator;
use WireUi\Traits\Actions;

class Configuration extends Component
{
    use Actions;

    public ?string $mail_mailer = null;

    public ?string $mail_host = null;

    public ?string $mail_port = null;

    public ?string $mail_username = null;

    public ?string $mail_password = null;

    public ?string $mail_encryption = null;

    public ?string $mail_from_address = null;

    public ?string $mail_from_name = null;

    public function mount(): void
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

    public function store(): void
    {
        $this->validate([
            'mail_from_address' => ['required', 'email', new RealEmailValidator()],
            'mail_from_name' => 'required',
        ]);

        Artisan::call('config:clear');

        setEnvironmentValue([
            'mail_mailer' => $this->mail_mailer,
            'mail_host' => $this->mail_host,
            'mail_port' => $this->mail_port,
            'mail_username' => $this->mail_username,
            'mail_password' => $this->mail_password,
            'mail_encryption' => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name' => $this->mail_from_name,
        ]);

        if (app()->environment('production')) {
            Artisan::call('config:cache');
        }

        $this->notification()->success(
            __('shopper::status.updated'),
            __('shopper::pages/settings.settings.notifications.email_config'),
        );
    }

    public function render()
    {
        return view('shopper::livewire.settings.mails.configuration');
    }
}
