<?php

namespace App\Actions\Dashboard;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Actions\Tip\SearchAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;

class ViewAction extends Action
{
    /**
     * Retrieve the tips written by people followed by the given user.
     *
     */
    public static function execute(User $user) : Paginator
    {
        return SearchAction::execute(static::query($user), $user);
    }

    /**
     * Generate the base query.
     *
     */
    protected static function query(User $user) : Builder
    {
        return Tip::join('followers', function($join) use ($user) {
            return $join->on('followers.teacher_id', '=', 'tips.user_id')
                ->where('followers.student_id', $user->id);
        });
    }
}
