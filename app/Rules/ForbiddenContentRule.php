<?php

namespace App\Rules;

use App\Types\Rule;
use Illuminate\Support\Str;

class ForbiddenContentRule extends Rule
{
    /**
     * Retrieve the validation error message.
     *
     */
    public function message() : string
    {
        return 'The supplied value cannot be used.';
    }

    /**
     * Determine if the validation rule passes.
     *
     */
    public function passes($attribute, $value) : bool
    {
        $value      = Str::lower($value);
        $current    = Str::lower($this->parameters[1]);
        $exclusions = config("system.forbidden.{$this->parameters[0]}");

        if (Str::endsWith($current, $exclusions)) {
            return true;
        }

        return ! Str::endsWith($value, $exclusions);
    }
}
