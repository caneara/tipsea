<?php

namespace App\Actions\Home;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Actions\Tip\SearchAction;
use Illuminate\Contracts\Pagination\Paginator;

class ViewAction extends Action
{
    /**
     * Retrieve the latest code tips.
     *
     */
    public static function execute(User $user = null, array $payload) : Paginator
    {
        return SearchAction::execute(Tip::query(), $user, $payload);
    }
}
