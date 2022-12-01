<?php

namespace App\Storage;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image as Intervention;

class Image
{
    /**
     * Determine whether an image with the given identifier exists.
     *
     */
    public static function assertExists(string $id) : void
    {
        Storage::assertExists("images/{$id}.jpg");
    }

    /**
     * Determine whether an image with the given identifier does not exist.
     *
     */
    public static function assertMissing(string $id) : void
    {
        Storage::assertMissing("images/{$id}.jpg");
    }

    /**
     * Delete one or more images with the given identifier(s).
     *
     */
    public static function delete(string | array | Collection $id = null) : void
    {
        collect($id)->filter()->each(fn($item) => Storage::delete("images/{$item}.jpg"));
    }

    /**
     * Delete the temporary image with the given identifier.
     *
     */
    public static function deleteTemporary(string $id) : void
    {
        Storage::delete("tmp/{$id}");
    }

    /**
     * Generate a fake image.
     *
     */
    public static function fake(string $id, string $path = '', string $format = '', bool $extension = true) : void
    {
        $path      = $path ? $path : 'images';
        $format    = $format ? $format : 'jpeg';
        $extension = $extension ? ".{$format}" : '';

        $image = imagecreatetruecolor(10, 10);

        Storage::makeDirectory($path);

        "image{$format}"($image, storage_path("framework/testing/{$path}/{$id}{$extension}"));

        imagedestroy($image);
    }

    /**
     * Generate a fake image in the temporary image directory.
     *
     */
    public static function fakeTemporary(string $id, string $format = 'png') : void
    {
        Storage::makeDirectory('tmp');

        static::fake($id, 'tmp', $format, false);
    }

    /**
     * Retrieve the image with the given identifier.
     *
     */
    public static function get(string $id) : string
    {
        return Storage::get("images/{$id}.jpg");
    }

    /**
     * Retrieve the temporary image with the given identifier.
     *
     */
    public static function getTemporary(string $id) : string
    {
        return Storage::get("tmp/{$id}");
    }

    /**
     * Save the given content to an image with the given identifier.
     *
     */
    public static function put(string $id, string | Intervention $content = '') : void
    {
        Storage::put("images/{$id}.jpg", (string) $content);
    }

    /**
     * Retrieve the URL to an image with the given identifier.
     *
     */
    public static function url(string $id) : string
    {
        return Storage::url("images/{$id}.jpg");
    }
}
