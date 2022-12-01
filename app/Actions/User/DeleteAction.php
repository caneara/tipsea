<?php

namespace App\Actions\User;

use App\Models\User;
use App\Types\Action;
use App\Storage\Image;
use App\Actions\Authentication\LogoutAction;

class DeleteAction extends Action
{
    /**
     * Delete the given user.
     *
     */
    public static function execute(User $user) : void
    {
        Image::delete($user->avatar);
        Image::delete($user->tips()->pluck('card'));
        Image::delete($user->banners()->pluck('graphic'));

        LogoutAction::execute();

        attempt(fn() => $user->delete());
    }
}
