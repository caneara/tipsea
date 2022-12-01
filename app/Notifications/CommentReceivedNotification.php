<?php

namespace App\Notifications;

use App\Types\Notification;
use Illuminate\Support\Str;
use Illuminate\Notifications\Messages\MailMessage;

class CommentReceivedNotification extends Notification
{
    /**
     * The incoming payload.
     *
     */
    public array $payload;

    /**
     * Constructor.
     *
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Retrieve the array representation of the notification.
     *
     */
    public function toArray($notifiable) : array
    {
        return $this->payload;
    }

    /**
     * Retrieve the mail representation of the notification.
     *
     */
    public function toMail($notifiable) : MailMessage
    {
        return $this->email()
            ->subject('New Comment - ' . Str::limit($this->payload['title'], 40))
            ->markdown('mail.notification.comment', $this->payload);
    }
}
