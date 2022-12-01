<?php

namespace App\Actions\Bookmark;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Actions\Tip\SearchAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;

class ViewAction extends Action
{
    /**
     * Retrieve the tips that have been bookmarked by the given user.
     *
     */
    public static function execute(User $user, array $payload) : Paginator
    {
        return SearchAction::execute(static::query($user), $user, $payload);
    }

    /**
     * Generate the base query.
     *
     */
    protected static function query(User $user) : Builder
    {
        return Tip::join('bookmarks', function($join) use ($user) {
            return $join->on('bookmarks.tip_id', '=', 'tips.id')
                ->where('bookmarks.user_id', $user->id);
        });
    }
}
