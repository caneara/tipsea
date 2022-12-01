<?php

namespace App\Actions\Follower;

use App\Models\User;
use App\Types\Action;
use App\Models\Follower;

class StoreAction extends Action
{
    /**
     * Create a new follower using the given student and teacher.
     *
     */
    public static function execute(User $student, User $teacher) : Follower
    {
        $payload = [
            'student_id' => $student->id,
        ];

        return attempt(fn() => $teacher->followers()->create($payload));
    }
}
