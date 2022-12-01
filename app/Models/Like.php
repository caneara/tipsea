<?php

namespace App\Models;

use App\Types\Model;
use App\Concerns\Like\Relationships;

class Like extends Model
{
    use Relationships;

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'      => 'integer',
        'tip_id'  => 'integer',
        'user_id' => 'integer',
    ];
}
