<?php

namespace App\Actions\Email;

use App\Models\User;
use App\Types\Action;

class VerifyAction extends Action
{
    /**
     * Mark the given user's email address as verified.
     *
     */
    public static function execute(User $user) : void
    {
        attempt(fn() => $user->markEmailAsVerified());
    }
}
