<?php

namespace App\Actions\Tip;

use App\Models\Tip;
use App\Types\Action;
use App\Storage\Image;

class DeleteAction extends Action
{
    /**
     * Delete the given tip.
     *
     */
    public static function execute(Tip $tip) : void
    {
        Image::delete($tip->card);

        attempt(fn() => $tip->delete());
    }
}
