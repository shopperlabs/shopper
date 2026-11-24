<?php

declare(strict_types=1);

namespace Shopper\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Shopper\Models\Contracts\ShopperUser;

final class AdminSendCredentials extends Notification
{
    public function __construct(
        public string $token
    ) {}

    /**
     * @return array<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(ShopperUser&Model $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('shopper::mails.admin.subject'))
            ->greeting(__('shopper::mails.admin.greeting', ['name' => $notifiable->full_name]))
            ->line(__('shopper::mails.admin.account_created', ['website' => config('app.url')]))
            ->line(__('shopper::mails.admin.set_password'))
            ->action(
                __('shopper::mails.admin.set_password_action'),
                route('shopper.password.reset', ['token' => $this->token, 'email' => $notifiable->email])
            )
            ->line(__('shopper::mails.admin.link_expiry', [
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]));
    }
}
