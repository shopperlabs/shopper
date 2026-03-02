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
            ->subject(__('Welcome to Shopper'))
            ->greeting(__('Hello :name', ['name' => $notifiable->full_name]))
            ->line(__('An account has been created for you as Administrator on the website :website', ['website' => config('app.url')]))
            ->line(__('Email: :email - Password: :password', ['email' => $notifiable->email, 'password' => $this->password]))
            ->line(__('You can use the following link to login:'))
            ->action(__('Login'), route('shopper.login'))
            ->line(__('After logging in you need to change your password by clicking on your name in the upper right corner of the admin area'));
    }
}
