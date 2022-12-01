<?php

namespace App\Actions\Comment;

use App\Types\Action;
use App\Models\Comment;

class UpdateAction extends Action
{
    /**
     * Update the given comment using the given payload.
     *
     */
    public static function execute(Comment $comment, array $payload) : Comment
    {
        return attempt(fn() => tap($comment)->update($payload));
    }
}
