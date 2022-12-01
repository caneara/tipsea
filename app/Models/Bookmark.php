<?php

namespace App\Models;

use App\Types\Model;
use App\Concerns\Bookmark\Relationships;

class Bookmark extends Model
{
    use Relationships;

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
        'tip_id'  => 'integer',
    ];
}
