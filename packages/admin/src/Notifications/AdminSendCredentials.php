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
        public string $password
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
            ->line(__('shopper::mails.admin.credentials', ['email' => $notifiable->email, 'password' => $this->password]))
            ->line(__('shopper::mails.admin.login_link'))
            ->action(__('shopper::mails.admin.action'), route('shopper.login'))
            ->line(__('shopper::mails.admin.change_password'));
    }
}
