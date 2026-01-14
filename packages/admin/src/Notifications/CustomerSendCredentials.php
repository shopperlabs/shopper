<?php

declare(strict_types=1);

namespace Shopper\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Shopper\Core\Models\Contracts\ShopperUser;

final class CustomerSendCredentials extends Notification
{
    public function __construct(public string $password) {}

    /**
     * @return array<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(ShopperUser $notifiable): MailMessage
    {
        /** @var \Illuminate\Database\Eloquent\Model $notifiable */
        return (new MailMessage)
            ->subject(__('Welcome to ').config('app.name'))
            ->greeting(__('Hello :full_name', ['full_name' => $notifiable->full_name]))
            ->line(__('An account has been created for you on the website ').config('app.url'))
            ->line(__('Email: :email - Password: :password', ['email' => $notifiable->email, 'password' => $this->password]))
            ->line(__('You can access to the website to login'))
            ->action(__('Browse the website'), url('/'))
            ->line(__('After logging in you have to change your password.'));
    }
}
