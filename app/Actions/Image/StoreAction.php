<?php

namespace App\Actions\Image;

use App\Types\Model;
use App\Types\Action;
use App\Storage\Image;

class StoreAction extends Action
{
    /**
     * Store the given image for the given model.
     *
     */
    public static function execute(Model $model, string $attribute) : void
    {
        $id = $model->getAttribute($attribute);

        Image::put($id, PaintAction::execute(class_basename($model), $id));
    }
}
