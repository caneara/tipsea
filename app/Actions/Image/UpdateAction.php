<?php

namespace App\Actions\Image;

use App\Types\Model;
use App\Types\Action;
use App\Storage\Image;

class UpdateAction extends Action
{
    /**
     * Update the given model with the given image.
     *
     */
    public static function execute(Model $model, string $attribute, string $id) : Model
    {
        Image::delete($model->getAttribute($attribute));

        $model = attempt(fn() => tap($model)->update([$attribute => $id]));

        StoreAction::execute($model, $attribute);

        return $model;
    }
}
