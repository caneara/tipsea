<?php

namespace App\Actions\Like;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;

class DeleteAction extends Action
{
    /**
     * Delete an existing like using the given user and tip.
     *
     */
    public static function execute(User $user, Tip $tip) : void
    {
        $query = $user->likes()
            ->where('tip_id', $tip->id);

        attempt(fn() => $query->delete());
    }
}
