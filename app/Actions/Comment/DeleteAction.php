<?php

namespace App\Actions\Comment;

use App\Types\Action;
use App\Models\Comment;

class DeleteAction extends Action
{
    /**
     * Delete the given comment.
     *
     */
    public static function execute(Comment $comment) : void
    {
        attempt(fn() => $comment->delete());
    }
}
