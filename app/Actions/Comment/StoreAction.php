<?php

namespace App\Actions\Comment;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Models\Comment;

class StoreAction extends Action
{
    /**
     * Create a comment from the given user for the given tip.
     *
     */
    public static function execute(User $user, Tip $tip, string $message) : Comment
    {
        $payload = [
            'user_id' => $user->id,
            'message' => $message,
        ];

        NotifyAction::execute($user, $tip, $message);

        return attempt(fn() => $tip->comments()->create($payload));
    }
}
