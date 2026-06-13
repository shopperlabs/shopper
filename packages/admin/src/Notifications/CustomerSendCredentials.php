<?php

declare(strict_types=1);

namespace Shopper\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Shopper\Models\Contracts\ShopperUser;

final class CustomerSendCredentials extends Notification
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
            ->subject(__('shopper::mails.customer.subject', ['name' => config('app.name')]))
            ->greeting(__('shopper::mails.customer.greeting', ['name' => $notifiable->full_name]))
            ->line(__('shopper::mails.customer.account_created', ['website' => config('app.url')]))
            ->line(__('shopper::mails.customer.credentials', ['email' => $notifiable->email, 'password' => $this->password]))
            ->line(__('shopper::mails.customer.can_login'))
            ->action(__('shopper::mails.customer.action'), url('/'))
            ->line(__('shopper::mails.customer.change_password'));
    }
}
