<?php

namespace App\Actions\Follower;

use App\Models\User;
use App\Types\Action;

class DeleteAction extends Action
{
    /**
     * Delete an existing follower using the given student and teacher.
     *
     */
    public static function execute(User $student, User $teacher) : void
    {
        $query = $teacher->followers()
            ->where('student_id', $student->id);

        attempt(fn() => $query->delete());
    }
}
