<?php

namespace App\Policies;

use App\Models\Tip;
use App\Models\User;
use App\Types\Policy;

class BookmarkPolicy extends Policy
{
    /**
     * Determine whether the given user can delete a bookmark for the given tip.
     *
     */
    public function delete(User $user, Tip $tip) : bool
    {
        return $user->bookmarks()
            ->where('tip_id', $tip->id)
            ->exists();
    }

    /**
     * Determine whether the given user can store a bookmark for the given tip.
     *
     */
    public function store(User $user, Tip $tip) : bool
    {
        if ($user->owns($tip)) {
            return false;
        }

        if (blank($tip->published_at) || $tip->published_at->isFuture()) {
            return false;
        }

        return $user->bookmarks()
            ->where('tip_id', $tip->id)
            ->doesntExist();
    }
}
