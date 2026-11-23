<?php

declare(strict_types=1);

namespace Shopper\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Shopper\Models\Contracts\ShopperUser;

final class CustomerSendCredentials extends ResetPassword
{
    /**
     * @param  ShopperUser&Model  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('shopper::mails.customer.subject', ['name' => config('app.name')]))
            ->greeting(__('shopper::mails.customer.greeting', ['name' => $notifiable->full_name]))
            ->line(__('shopper::mails.customer.account_created', ['website' => config('app.url')]))
            ->line(__('shopper::mails.customer.set_password'))
            ->action(__('shopper::mails.customer.set_password_action'), $this->resetUrl($notifiable))
            ->line(__('shopper::mails.customer.link_expiry', [
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]));
    }
}
