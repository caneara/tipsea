<?php

namespace App\Policies;

use App\Models\User;
use App\Types\Policy;

class FollowerPolicy extends Policy
{
    /**
     * Determine whether the given user can cease being a student of the given teacher.
     *
     */
    public function delete(User $user, User $teacher) : bool
    {
        return $teacher->followers()
            ->where('student_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the given user can become a student of the given teacher.
     *
     */
    public function store(User $user, User $teacher) : bool
    {
        $missing = $teacher->followers()
            ->where('student_id', $user->id)
            ->doesntExist();

        return $missing && $user->isNot($teacher);
    }
}
