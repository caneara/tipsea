<?php

namespace App\Actions\Image;

use App\Types\Action;
use Intervention\Image\Image;
use App\Storage\Image as Storage;
use Intervention\Image\Facades\Image as Intervention;

class PaintAction extends Action
{
    /**
     * Create a formatted image from the given identifier.
     *
     */
    public static function execute(string $type, string $id) : string
    {
        $image = Intervention::make(Storage::getTemporary($id));

        Storage::deleteTemporary($id);

        $rendered = static::format($image, $type)->encode('jpg', 80);

        $image->destroy();

        return $rendered;
    }

    /**
     * Format the given image according to its configured dimensions.
     *
     */
    protected static function format(Image $image, string $type) : Image
    {
        $width  = config("system.image_dimensions.{$type}.width");
        $height = config("system.image_dimensions.{$type}.height");
        $action = config("system.image_dimensions.{$type}.action");

        return static::$action($image, $width, $height);
    }

    /**
     * Force the given image into the given width and height dimensions.
     *
     */
    protected static function fit(Image $image, int $width, int $height) : Image
    {
        return $image->fit($width, $height, function($constraint) {
            return $constraint->upsize();
        });
    }

    /**
     * Adjust the given image to the given width and height dimensions.
     *
     */
    protected static function resize(Image $image, int $width, int $height) : Image
    {
        return $image->resize($width, $height);
    }
}
