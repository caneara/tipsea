<?php

namespace App\Policies;

use App\Models\Tip;
use App\Models\User;
use App\Types\Policy;
use App\Models\Comment;

class CommentPolicy extends Policy
{
    /**
     * Determine whether the given user can delete the given comment.
     *
     */
    public function delete(User $user, Comment $comment) : bool
    {
        return $user->owns($comment);
    }

    /**
     * Determine whether the given user can reply to the given comment.
     *
     */
    public function reply(User $user, Comment $comment) : bool
    {
        return $comment->user->isNot($user)
            && $comment->tip->published_at
            && $comment->tip->published_at->isPast();
    }

    /**
     * Determine whether the given user can store a comment for the given tip.
     *
     */
    public function store(User $user, Tip $tip) : bool
    {
        return $tip->published_at
            && $tip->published_at->isPast();
    }

    /**
     * Determine whether the given user can update the given comment.
     *
     */
    public function update(User $user, Comment $comment) : bool
    {
        return $user->owns($comment);
    }
}
