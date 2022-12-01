<?php

namespace App\Actions\Comment;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Models\Comment;
use App\Enums\NotificationType;
use App\Actions\Notification\StoreAction;
use App\Notifications\CommentReceivedNotification;

class NotifyAction extends Action
{
    /**
     * Advise the owner of the given tip.
     *
     */
    public static function execute(User $user, Comment | Tip $source, string $message) : void
    {
        $tip = $source instanceof Tip ? $source : $source->tip;

        StoreAction::execute($source->user, $user, $tip, NotificationType::COMMENT, $message);

        if (! setting($source->user, 'notifications_email_comments')) {
            return;
        }

        $payload = [
            'user'    => $user->name,
            'title'   => $tip->title,
            'message' => $message,
            'url'     => route('tips.show', ['tip' => $tip]),
        ];

        $source->user->notify(new CommentReceivedNotification($payload));
    }
}
