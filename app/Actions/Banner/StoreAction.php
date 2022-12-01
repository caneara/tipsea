<?php

namespace App\Actions\Banner;

use App\Models\User;
use App\Types\Action;
use App\Models\Banner;
use App\Actions\Image\StoreAction as ImageAction;

class StoreAction extends Action
{
    /**
     * Create a new banner using the given user and payload.
     *
     */
    public static function execute(User $user, array $payload) : Banner
    {
        $banner = attempt(fn() => $user->banners()->create($payload));

        ImageAction::execute($banner, 'graphic');

        return $banner;
    }
}
