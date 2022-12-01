<?php

namespace App\Rules;

use App\Types\Rule;
use Illuminate\Support\Arr;

class UniqueTagRule extends Rule
{
    /**
     * The tag attribute names.
     *
     */
    protected array $tags = [
        'first_tag'  => 0,
        'second_tag' => 0,
        'third_tag'  => 0,
        'fourth_tag' => 0,
    ];

    /**
     * Retrieve the validation error message.
     *
     */
    public function message() : string
    {
        return "The :attribute must be unique.";
    }

    /**
     * Determine if the validation rule passes.
     *
     */
    public function passes($attribute, $value) : bool
    {
        $others = Arr::except($this->tags, $attribute);

        return ! in_array($value, request()->only(array_keys($others)));
    }
}
