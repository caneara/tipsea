<?php

namespace App\Models;

use App\Types\Model;
use App\Concerns\Tip\Relationships;

class Tip extends Model
{
    use Relationships;

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'           => 'integer',
        'user_id'      => 'integer',
        'banner_id'    => 'integer',
        'title'        => 'string',
        'slug'         => 'string',
        'summary'      => 'string',
        'teaser'       => 'string',
        'theme'        => 'string',
        'gradient'     => 'integer',
        'card'         => 'string',
        'content'      => 'string',
        'attribution'  => 'string',
        'metrics'      => 'array',
        'shared'       => 'boolean',
        'published_at' => 'datetime',
    ];
}
