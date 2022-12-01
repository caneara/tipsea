<?php

namespace App\Rules;

use Exception;
use App\Types\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageRule extends Rule
{
    /**
     * The valid file formats.
     *
     */
    protected array $formats = [
        'image/png',
        'image/jpeg',
    ];

    /**
     * Determine if the validation rule passes.
     *
     */
    public function passes($attribute, $value) : bool
    {
        if (Storage::missing("tmp/{$value}")) {
            return $this->fail('The file does not exist.');
        }

        if (Storage::size("tmp/{$value}") > 1048576) {
            return $this->fail('The file exceeds 1 MB in size.');
        }

        if (! in_array(Storage::mimeType("tmp/{$value}"), $this->formats)) {
            return $this->fail('The file is not a JPEG or PNG.');
        }

        try {
            Image::make(Storage::get("tmp/{$value}"));
        } catch (Exception) {
            return $this->fail('The file could not be processed. Export it as a true JPEG or PNG, then try again.');
        }

        return true;
    }
}
