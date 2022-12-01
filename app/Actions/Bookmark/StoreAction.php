<?php

namespace App\Actions\Bookmark;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Models\Bookmark;

class StoreAction extends Action
{
    /**
     * Create a new bookmark using the given user and tip.
     *
     */
    public static function execute(User $user, Tip $tip) : Bookmark
    {
        $payload = [
            'tip_id' => $tip->id,
        ];

        return attempt(fn() => $user->bookmarks()->create($payload));
    }
}
