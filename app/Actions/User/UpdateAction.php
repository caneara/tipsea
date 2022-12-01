<?php

namespace App\Actions\User;

use App\Models\User;
use App\Types\Action;
use Illuminate\Support\Arr;
use App\Actions\Email\NotifyAction;
use App\Actions\Image\UpdateAction as ImageAction;

class UpdateAction extends Action
{
    /**
     * Update the given user using the given payload.
     *
     */
    public static function execute(User $user, array $payload) : User
    {
        static::make()->when($payload['email'] ?? '', function() use ($user, $payload) {
            $user->email !== $payload['email'] ? NotifyAction::execute($user) : null;
        });

        static::make()->when($payload['avatar'] ?? '', function() use ($user, $payload) {
            ImageAction::execute($user, 'avatar', $payload['avatar']);
        });

        $attributes = Arr::except($payload, 'avatar');

        return attempt(fn() => tap($user)->update($attributes));
    }
}
