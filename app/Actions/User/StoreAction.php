<?php

namespace App\Actions\User;

use App\Models\User;
use App\Types\Action;

class StoreAction extends Action
{
    /**
     * Create a new user using the given payload.
     *
     */
    public static function execute(array $payload) : User
    {
        return attempt(fn() => User::create($payload));
    }
}
