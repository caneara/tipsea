<?php

namespace App\Notifications;

use App\Types\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     */
    public string $token;

    /**
     * Constructor.
     *
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Retrieve the mail representation of the notification.
     *
     */
    public function toMail($notifiable) : MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        return $this->email()
            ->subject('Reset Password')
            ->markdown('mail.password.reset', compact('url'));
    }
}
