<?php

namespace App\Actions\Profile;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Actions\Tip\SearchAction;
use Illuminate\Contracts\Pagination\Paginator;

class ShowAction extends Action
{
    /**
     * Retrieve the tips written by the given user.
     *
     */
    public static function execute(User $user) : Paginator
    {
        $payload = [
            'follower' => false,
        ];

        $query = Tip::where('user_id', $user->id);

        return SearchAction::execute($query, $user, $payload);
    }

    /**
     * Determine if the given student is a follower of the given teacher.
     *
     */
    public static function follower(User $student = null, User $teacher) : bool
    {
        return blank($student) ? false : $teacher->followers()
            ->where('student_id', $student->id)
            ->exists();
    }
}
