<?php

namespace App\Actions\Like;

use App\Models\Tip;
use App\Models\Like;
use App\Models\User;
use App\Types\Action;
use App\Models\Notification;
use App\Enums\NotificationType;
use App\Actions\Notification\StoreAction as StoreNotificationAction;

class StoreAction extends Action
{
    /**
     * Create a new like using the given user and tip.
     *
     */
    public static function execute(User $user, Tip $tip) : Like
    {
        $payload = [
            'tip_id' => $tip->id,
        ];

        if (static::notify($user, $tip)) {
            StoreNotificationAction::execute($tip->user, $user, $tip, NotificationType::LIKE);
        }

        return attempt(fn() => $user->likes()->create($payload));
    }

    /**
     * Determine whether to create a notification for the like.
     *
     */
    protected static function notify(User $user, Tip $tip) : bool
    {
        return Notification::query()
            ->where('tip_id', $tip->id)
            ->where('student_id', $user->id)
            ->doesntExist();
    }
}
