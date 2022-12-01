<?php

namespace App\Policies;

use App\Models\Tip;
use App\Models\User;
use App\Types\Policy;

class TipPolicy extends Policy
{
    /**
     * Determine whether the given user can delete the given tip.
     *
     */
    public function delete(User $user, Tip $tip) : bool
    {
        return $user->type->isEmployee() || $user->owns($tip);
    }

    /**
     * Determine whether the given user can edit the given tip.
     *
     */
    public function edit(User $user, Tip $tip) : bool
    {
        return $user->owns($tip);
    }

    /**
     * Determine whether the given user can show the given tip.
     *
     */
    public function show(User $user = null, Tip $tip) : bool
    {
        return ($user && $user->owns($tip)) ||
            ($tip->published_at && $tip->published_at->isPast());
    }

    /**
     * Determine whether the given user can update the given tip.
     *
     */
    public function update(User $user, Tip $tip) : bool
    {
        return $user->owns($tip);
    }
}
