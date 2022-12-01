<?php

namespace App\Enums;

enum NotificationType : int
{
    /**
     * The available options.
     *
     */
    case LIKE    = 1;
    case COMMENT = 2;

    /**
     * Retrieve the name of the current instance.
     *
     */
    public function name() : string
    {
        return match ($this) {
            static::LIKE    => 'like',
            static::COMMENT => 'comment',
        };
    }
}
