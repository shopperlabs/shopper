<?php

declare(strict_types=1);

namespace Shopper\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class AdminResetPassword extends Notification
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

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('shopper::pages/auth.email.mail.subject'))
            ->line(__('shopper::pages/auth.email.mail.content'))
            ->action(
                __('shopper::pages/auth.email.mail.action'),
                route('shopper.password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(), // @phpstan-ignore-line
                ])
            )
            ->line(__('shopper::pages/auth.email.mail.message'));
    }
}
