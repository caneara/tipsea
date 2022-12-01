<?php

namespace App\Types;

use Illuminate\Auth\Access\HandlesAuthorization;

abstract class Policy
{
    use HandlesAuthorization;

    /**
     * Factory method.
     *
     */
    public static function make() : static
    {
        return new static();
    }
}
