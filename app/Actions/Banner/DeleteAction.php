<?php

namespace App\Actions\Banner;

use App\Types\Action;
use App\Models\Banner;
use App\Storage\Image;

class DeleteAction extends Action
{
    /**
     * Delete the given banner.
     *
     */
    public static function execute(Banner $banner) : void
    {
        Image::delete($banner->graphic);

        attempt(fn() => $banner->tips()->update(['banner_id' => null]));

        attempt(fn() => $banner->delete());
    }
}
