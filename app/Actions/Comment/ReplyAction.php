<?php

namespace App\Actions\Comment;

use App\Models\User;
use App\Types\Action;
use App\Models\Comment;

class ReplyAction extends Action
{
    /**
     * Create a comment from the given user for the given comment.
     *
     */
    public static function execute(User $user, Comment $comment, string $message) : Comment
    {
        $payload = [
            'user_id'   => $user->id,
            'tip_id'    => $comment->tip_id,
            'parent_id' => $comment->id,
            'message'   => $message,
        ];

        NotifyAction::execute($user, $comment, $message);

        return attempt(fn() => Comment::create($payload));
    }
}
