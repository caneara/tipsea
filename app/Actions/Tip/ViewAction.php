<?php

namespace App\Actions\Tip;

use App\Models\User;
use App\Types\Action;
use Illuminate\Contracts\Pagination\Paginator;

class ViewAction extends Action
{
    /**
     * Retrieve the code tips belonging to the given user.
     *
     */
    public static function execute(User $user, array $payload) : Paginator
    {
        $payload = array_merge($payload, [
            'follower'   => false,
            'published'  => false,
            'bookmarked' => false,
            'ordering'   => 'tips.created_at',
        ]);

        return SearchAction::execute($user->tips(), $user, $payload);
    }
}
