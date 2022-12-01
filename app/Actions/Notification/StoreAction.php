<?php

namespace App\Actions\Notification;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Enums\NotificationType;

class StoreAction extends Action
{
    /**
     * Store a new notification for the given teacher.
     *
     */
    public static function execute(User $teacher, User $student, Tip $tip, NotificationType $type, string $message = null) : void
    {
        if ($teacher->is($student)) {
            return;
        }

        $attributes = [
            'student_id' => $student->id,
            'tip_id'     => $tip->id,
            'type'       => $type,
            'message'    => $message,
        ];

        attempt(fn() => $teacher->notifications()->create($attributes));
    }
}
