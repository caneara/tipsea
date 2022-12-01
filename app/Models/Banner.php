<?php

namespace App\Models;

use App\Types\Model;
use App\Concerns\Banner\Relationships;

class Banner extends Model
{
    use Relationships;

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
        'name'    => 'string',
        'url'     => 'string',
        'graphic' => 'string',
    ];
}
