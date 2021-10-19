<?php

namespace Shopper\Framework\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerSendCredentials extends Notification
{
    /**
     * Password Attribute.
     *
     * @var string
     */
    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(__('Welcome to ') . env('APP_NAME'))
            ->greeting(__('Hello :full_name', ['full_name' => $notifiable->full_name]))
            ->line(__('An account has been created for you on the website ') . env('APP_URL'))
            ->line(__('Email: :email - Password: :password', ['email' => $notifiable->email, 'password' => $this->password]))
            ->line(__('You can access to the website to login'))
            ->action(__('Browse the website'), url('/'))
            ->line(__('After logging in you have to change your password.'));
    }
}
