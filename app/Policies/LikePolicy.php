<?php

namespace App\Policies;

use App\Models\Tip;
use App\Models\User;
use App\Types\Policy;

class LikePolicy extends Policy
{
    /**
     * Determine whether the given user can cease to like the given tip.
     *
     */
    public function delete(User $user, Tip $tip) : bool
    {
        return $user->likes()
            ->where('tip_id', $tip->id)
            ->exists();
    }

    /**
     * Determine whether the given user can like the given tip.
     *
     */
    public function store(User $user, Tip $tip) : bool
    {
        $missing = $user->likes()
            ->where('tip_id', $tip->id)
            ->doesntExist();

        $published = $tip->published_at && $tip->published_at->isPast();

        return $missing && $published && ! $user->owns($tip);
    }
}
