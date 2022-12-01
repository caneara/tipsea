<?php

namespace App\Actions\Bookmark;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;

class DeleteAction extends Action
{
    /**
     * Delete an existing bookmark using the given user and tip.
     *
     */
    public static function execute(User $user, Tip $tip) : void
    {
        attempt(fn() => $user->bookmarks()->where('tip_id', $tip->id)->delete());
    }
}
